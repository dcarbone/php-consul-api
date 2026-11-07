<?php

namespace DCarbone\PHPConsulAPITests\Integration;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
 */

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPITests\ConsulEnvTrait;
use DCarbone\PHPConsulAPITests\ConsulManager;
use PHPUnit\Framework\TestCase;

/**
 * Exercises Config states end-to-end against a real agent listening on plain HTTP, a unix socket, and HTTPS with
 * required client certificates, with ACLs enabled.
 */
final class ConfigStatesTest extends TestCase
{
    use ConsulEnvTrait;

    private const BOGUS_TOKEN = '11111111-2222-3333-4444-555555555555';

    /** @var string[] */
    private array $tempFiles = [];

    public static function setUpBeforeClass(): void
    {
        ConsulManager::startSingleDevTLS();
    }

    public static function tearDownAfterClass(): void
    {
        ConsulManager::stopSingle();
    }

    protected function setUp(): void
    {
        $this->isolateConsulEnv();
    }

    protected function tearDown(): void
    {
        $this->restoreConsulEnv();
        foreach ($this->tempFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $this->tempFiles = [];
    }

    public function testPlainAddress(): void
    {
        $this->assertLeader(new Consul(new Config(Address: ConsulManager::TEST_ADDRESS)));
    }

    public function testHttpSchemeInAddress(): void
    {
        $this->assertLeader(new Consul(new Config(Address: 'http://' . ConsulManager::TEST_ADDRESS)));
    }

    public function testEnvAddress(): void
    {
        $this->setEnv(Consul::HTTPAddrEnvName, 'http://' . ConsulManager::TEST_ADDRESS);
        $this->assertLeader(new Consul());
    }

    public function testPathPrefixIsSentToAgent(): void
    {
        $consul = new Consul(new Config(Address: 'http://' . ConsulManager::TEST_ADDRESS . '/some/proxy'));
        [, $err] = $consul->Status->Leader();
        self::assertInstanceOf(Error::class, $err, 'Expected agent to reject prefixed path');
        self::assertStringContainsString('404', $err->getMessage());
    }

    public function testUnixSocketAddress(): void
    {
        if (!extension_loaded('curl')) {
            self::markTestSkipped('ext-curl is required for unix socket support');
        }
        $this->assertLeader(new Consul(new Config(Address: 'unix://' . ConsulManager::UNIX_SOCKET)));
    }

    public function testUnixSocketEnvAddress(): void
    {
        if (!extension_loaded('curl')) {
            self::markTestSkipped('ext-curl is required for unix socket support');
        }
        $this->setEnv(Consul::HTTPAddrEnvName, 'unix://' . ConsulManager::UNIX_SOCKET);
        $this->assertLeader(new Consul());
    }

    public function testExplicitTokenSent(): void
    {
        $this->assertTokenSelf(new Consul(ConsulManager::testConfig(ConsulManager::MANAGEMENT_TOKEN)));
    }

    public function testEnvTokenSent(): void
    {
        $this->setEnv(Consul::HTTPTokenEnvName, ConsulManager::MANAGEMENT_TOKEN);
        $this->assertTokenSelf(new Consul(new Config(Address: ConsulManager::TEST_ADDRESS)));
    }

    public function testExplicitTokenOverridesEnvToken(): void
    {
        $this->setEnv(Consul::HTTPTokenEnvName, self::BOGUS_TOKEN);
        $this->assertTokenSelf(new Consul(ConsulManager::testConfig(ConsulManager::MANAGEMENT_TOKEN)));
    }

    public function testExplicitBogusTokenOverridesEnvToken(): void
    {
        $this->setEnv(Consul::HTTPTokenEnvName, ConsulManager::MANAGEMENT_TOKEN);
        $consul = new Consul(ConsulManager::testConfig(self::BOGUS_TOKEN));
        [, , $err] = $consul->ACL->TokenReadSelf();
        self::assertInstanceOf(Error::class, $err, 'Expected bogus explicit token to be used');
        self::assertStringContainsString('403', $err->getMessage());
    }

    public function testTokenFileSent(): void
    {
        $file = $this->tokenFile(ConsulManager::MANAGEMENT_TOKEN);
        $this->assertTokenSelf(new Consul(new Config(Address: ConsulManager::TEST_ADDRESS, TokenFile: $file)));
    }

    public function testEnvTokenFileSent(): void
    {
        $this->setEnv(Consul::HTTPTokenFileEnvName, $this->tokenFile(ConsulManager::MANAGEMENT_TOKEN));
        $this->assertTokenSelf(new Consul(new Config(Address: ConsulManager::TEST_ADDRESS)));
    }

    public function testExplicitTokenFileOverridesEnvToken(): void
    {
        $this->setEnv(Consul::HTTPTokenEnvName, self::BOGUS_TOKEN);
        $file = $this->tokenFile(ConsulManager::MANAGEMENT_TOKEN);
        $this->assertTokenSelf(new Consul(new Config(Address: ConsulManager::TEST_ADDRESS, TokenFile: $file)));
    }

    public function testHttpsWithCAFileAndClientCert(): void
    {
        $this->assertLeader(new Consul(new Config(
            Address: ConsulManager::TEST_HTTPS_ADDRESS,
            Scheme: 'https',
            CAFile: ConsulManager::TLS_CA_FILE,
            CertFile: ConsulManager::TLS_CLIENT_CERT_FILE,
            KeyFile: ConsulManager::TLS_CLIENT_KEY_FILE,
        )));
    }

    public function testHttpsSchemeInAddress(): void
    {
        $this->assertLeader(new Consul(new Config(
            Address: 'https://' . ConsulManager::TEST_HTTPS_ADDRESS,
            CAFile: ConsulManager::TLS_CA_FILE,
            CertFile: ConsulManager::TLS_CLIENT_CERT_FILE,
            KeyFile: ConsulManager::TLS_CLIENT_KEY_FILE,
        )));
    }

    public function testHttpsWithCAPathAndClientCert(): void
    {
        if (!ConsulManager::hasCAPathDir()) {
            self::markTestSkipped('Unable to create hashed CA directory (openssl unavailable)');
        }
        $this->assertLeader(new Consul(new Config(
            Address: ConsulManager::TEST_HTTPS_ADDRESS,
            Scheme: 'https',
            CertFile: ConsulManager::TLS_CLIENT_CERT_FILE,
            KeyFile: ConsulManager::TLS_CLIENT_KEY_FILE,
            CAPath: ConsulManager::TLS_CA_PATH_DIR,
        )));
    }

    public function testHttpsInsecureSkipVerifyWithClientCert(): void
    {
        $this->assertLeader(new Consul(new Config(
            Address: ConsulManager::TEST_HTTPS_ADDRESS,
            Scheme: 'https',
            CertFile: ConsulManager::TLS_CLIENT_CERT_FILE,
            KeyFile: ConsulManager::TLS_CLIENT_KEY_FILE,
            InsecureSkipVerify: true,
        )));
    }

    public function testHttpsFromEnv(): void
    {
        $this->setEnv(Consul::HTTPAddrEnvName, ConsulManager::TEST_HTTPS_ADDRESS);
        $this->setEnv(Consul::HTTPSSLEnvName, 'true');
        $this->setEnv(Consul::HTTPCAFileEnvName, ConsulManager::TLS_CA_FILE);
        $this->setEnv(Consul::HTTPClientCertEnvName, ConsulManager::TLS_CLIENT_CERT_FILE);
        $this->setEnv(Consul::HTTPClientKeyEnvName, ConsulManager::TLS_CLIENT_KEY_FILE);
        $this->assertLeader(new Consul());
    }

    public function testHttpsEnvSSLVerifyFalse(): void
    {
        $this->setEnv(Consul::HTTPAddrEnvName, 'https://' . ConsulManager::TEST_HTTPS_ADDRESS);
        $this->setEnv(Consul::HTTPSSLVerifyEnvName, 'false');
        $this->setEnv(Consul::HTTPClientCertEnvName, ConsulManager::TLS_CLIENT_CERT_FILE);
        $this->setEnv(Consul::HTTPClientKeyEnvName, ConsulManager::TLS_CLIENT_KEY_FILE);
        $this->assertLeader(new Consul());
    }

    public function testHttpsWithoutCAFails(): void
    {
        $consul = new Consul(new Config(
            Address: ConsulManager::TEST_HTTPS_ADDRESS,
            Scheme: 'https',
            CertFile: ConsulManager::TLS_CLIENT_CERT_FILE,
            KeyFile: ConsulManager::TLS_CLIENT_KEY_FILE,
        ));
        [, $err] = $consul->Status->Leader();
        self::assertInstanceOf(Error::class, $err, 'Expected server certificate verification to fail');
    }

    public function testHttpsWithoutClientCertFails(): void
    {
        $consul = new Consul(new Config(
            Address: ConsulManager::TEST_HTTPS_ADDRESS,
            Scheme: 'https',
            CAFile: ConsulManager::TLS_CA_FILE,
        ));
        [, $err] = $consul->Status->Leader();
        self::assertInstanceOf(Error::class, $err, 'Expected agent to require a client certificate');
    }

    public function testHttpAgainstHttpsPortFails(): void
    {
        $consul = new Consul(new Config(Address: 'http://' . ConsulManager::TEST_HTTPS_ADDRESS));
        [, $err] = $consul->Status->Leader();
        self::assertInstanceOf(Error::class, $err);
    }

    private function assertLeader(Consul $consul): void
    {
        [$leader, $err] = $consul->Status->Leader();
        self::assertNull($err, sprintf('Status::Leader returned error: %s', $err));
        self::assertIsString($leader);
        self::assertNotSame('', $leader);
    }

    private function assertTokenSelf(Consul $consul): void
    {
        [$token, , $err] = $consul->ACL->TokenReadSelf();
        self::assertNull($err, sprintf('ACL::TokenReadSelf returned error: %s', $err));
        self::assertNotNull($token);
        self::assertSame(ConsulManager::MANAGEMENT_TOKEN, $token->SecretID);
    }

    private function tokenFile(string $token): string
    {
        $file = tempnam(sys_get_temp_dir(), 'consul-token-');
        self::assertIsString($file);
        file_put_contents($file, $token . "\n");
        $this->tempFiles[] = $file;
        return $file;
    }
}
