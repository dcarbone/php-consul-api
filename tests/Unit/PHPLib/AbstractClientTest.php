<?php

namespace DCarbone\PHPConsulAPITests\Unit\PHPLib;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\HttpAuth;
use DCarbone\PHPConsulAPI\PHPLib\AbstractClient;
use DCarbone\PHPConsulAPI\PHPLib\Request;
use DCarbone\PHPConsulAPITests\ConsulEnvTrait;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use PHPUnit\Framework\TestCase;

final class AbstractClientTest extends TestCase
{
    use ConsulEnvTrait;

    protected function setUp(): void
    {
        $this->isolateConsulEnv();
    }

    protected function tearDown(): void
    {
        $this->restoreConsulEnv();
    }

    private function makeClient(Config $config): object
    {
        return new class($config) extends AbstractClient {
            /**
             * @return array<string,mixed>
             */
            public function buildGuzzleRequestOptions(Request $request): array
            {
                return $this->_buildGuzzleRequestOptions($request);
            }
        };
    }

    public function testTLSVerificationEnabledByDefault(): void
    {
        $config = new Config(InsecureSkipVerify: false);
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertArrayNotHasKey(GuzzleRequestOptions::VERIFY, $options);
    }

    public function testInsecureSkipVerifyDisablesTLSVerification(): void
    {
        $config = new Config(CAFile: '/path/to/ca.pem', InsecureSkipVerify: true);
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertFalse($options[GuzzleRequestOptions::VERIFY]);
    }

    public function testHttpAuthNotSentWhenUnset(): void
    {
        $config = new Config();
        $config->setHttpAuth(httpAuth: null);
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertArrayNotHasKey(GuzzleRequestOptions::AUTH, $options);
    }

    public function testHttpAuthStringSentAsBasicAuth(): void
    {
        $config = new Config(HttpAuth: 'user:p:ss');
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertSame(['user', 'p:ss'], $options[GuzzleRequestOptions::AUTH]);
    }

    public function testHttpAuthObjectSentAsBasicAuth(): void
    {
        $config = new Config(HttpAuth: new HttpAuth(username: 'user', password: 'pass'));
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertSame(['user', 'pass'], $options[GuzzleRequestOptions::AUTH]);
    }

    public function testHttpAuthUsernameOnlySentWithEmptyPassword(): void
    {
        $config = new Config(HttpAuth: 'user');
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertSame(['user', ''], $options[GuzzleRequestOptions::AUTH]);
    }

    public function testCAFileUsedForTLSVerification(): void
    {
        $config = new Config(CAFile: '/path/to/ca.pem', InsecureSkipVerify: false);
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertSame('/path/to/ca.pem', $options[GuzzleRequestOptions::VERIFY]);
    }

    public function testCAPathUsedForTLSVerification(): void
    {
        $config = new Config(InsecureSkipVerify: false, CAPath: '/path/to/certs');
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertSame('/path/to/certs', $options[GuzzleRequestOptions::VERIFY]);
    }

    public function testCAFileTakesPrecedenceOverCAPath(): void
    {
        $config = new Config(CAFile: '/path/to/ca.pem', InsecureSkipVerify: false, CAPath: '/path/to/certs');
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertSame('/path/to/ca.pem', $options[GuzzleRequestOptions::VERIFY]);
    }

    public function testInsecureSkipVerifyTakesPrecedenceOverCAFileAndCAPath(): void
    {
        $config = new Config(CAFile: '/path/to/ca.pem', InsecureSkipVerify: true, CAPath: '/path/to/certs');
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertFalse($options[GuzzleRequestOptions::VERIFY]);
    }

    public function testClientCertAndKeyApplied(): void
    {
        $config = new Config(CertFile: '/path/to/client.crt', KeyFile: '/path/to/client.key');
        $options = $this->makeClient($config)->buildGuzzleRequestOptions(new Request('GET', 'test', $config, null));
        self::assertSame('/path/to/client.crt', $options[GuzzleRequestOptions::CERT]);
        self::assertSame('/path/to/client.key', $options[GuzzleRequestOptions::SSL_KEY]);
    }

    public function testConsulEnvSSLVerifyFalseOverridesExplicitFalseLikeGo(): void
    {
        // Go's NewClient: if !config.InsecureSkipVerify { config.InsecureSkipVerify = def.InsecureSkipVerify }
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: 'false');
        $options = $this->buildOptionsThroughConsul(new Config(InsecureSkipVerify: false));
        self::assertFalse($options[GuzzleRequestOptions::VERIFY]);
    }

    public function testConsulAppliesEnvSSLVerifyWhenNotExplicit(): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: 'false');
        $options = $this->buildOptionsThroughConsul(new Config());
        self::assertFalse($options[GuzzleRequestOptions::VERIFY]);
    }

    public function testConsulExplicitSkipVerifyKeptWhenEnvRequestsVerify(): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: 'true');
        $options = $this->buildOptionsThroughConsul(new Config(InsecureSkipVerify: true));
        self::assertFalse($options[GuzzleRequestOptions::VERIFY]);
    }

    public function testConsulPreservesClearedHttpAuthOverEnv(): void
    {
        $this->setEnv(name: Consul::HTTPAuthEnvName, value: 'envuser:envpass');
        $config = new Config();
        $config->setHttpAuth(httpAuth: null);
        $options = $this->buildOptionsThroughConsul($config);
        self::assertArrayNotHasKey(GuzzleRequestOptions::AUTH, $options);
    }

    public function testConsulAppliesEnvHttpAuthWhenNotExplicit(): void
    {
        $this->setEnv(name: Consul::HTTPAuthEnvName, value: 'envuser:envpass');
        $options = $this->buildOptionsThroughConsul(new Config());
        self::assertSame(['envuser', 'envpass'], $options[GuzzleRequestOptions::AUTH]);
    }

    public function testExplicitContentTypeHeaderIsPreservedRegardlessOfCase(): void
    {
        $config = new Config();
        $client = $this->makeClient($config);

        foreach (['CONTENT-TYPE', 'Content-type'] as $headerName) {
            $request = new Request('POST', 'test', $config, ['payload' => 'value']);
            $request->header->set($headerName, 'application/custom');

            $options = $client->buildGuzzleRequestOptions($request);

            self::assertArrayNotHasKey(GuzzleRequestOptions::HEADERS, $options);
            self::assertSame('application/custom', $request->header->get('Content-Type'));
            self::assertSame('application/custom', $request->toPsrRequest()->getHeaderLine('Content-Type'));
        }
    }

    /**
     * @return array<string,mixed>
     */
    private function buildOptionsThroughConsul(Config $config): array
    {
        $merged = (new Consul(config: $config))->KV->getConfig();
        return $this->makeClient($merged)->buildGuzzleRequestOptions(new Request('GET', 'test', $merged, null));
    }
}
