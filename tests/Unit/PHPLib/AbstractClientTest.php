<?php

namespace DCarbone\PHPConsulAPITests\Unit\PHPLib;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\HttpAuth;
use DCarbone\PHPConsulAPI\PHPLib\AbstractClient;
use DCarbone\PHPConsulAPI\PHPLib\Request;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use PHPUnit\Framework\TestCase;

final class AbstractClientTest extends TestCase
{
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

    public function testConsulPreservesExplicitTLSVerificationOverEnv(): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: 'false');
        try {
            $options = $this->buildOptionsThroughConsul(new Config(InsecureSkipVerify: false));
            self::assertArrayNotHasKey(GuzzleRequestOptions::VERIFY, $options);
        } finally {
            $this->clearEnv(Consul::HTTPSSLVerifyEnvName);
        }
    }

    public function testConsulAppliesEnvSSLVerifyWhenNotExplicit(): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: 'false');
        try {
            $options = $this->buildOptionsThroughConsul(new Config());
            self::assertFalse($options[GuzzleRequestOptions::VERIFY]);
        } finally {
            $this->clearEnv(Consul::HTTPSSLVerifyEnvName);
        }
    }

    public function testConsulPreservesClearedHttpAuthOverEnv(): void
    {
        $this->setEnv(name: Consul::HTTPAuthEnvName, value: 'envuser:envpass');
        try {
            $config = new Config();
            $config->setHttpAuth(httpAuth: null);
            $options = $this->buildOptionsThroughConsul($config);
            self::assertArrayNotHasKey(GuzzleRequestOptions::AUTH, $options);
        } finally {
            $this->clearEnv(Consul::HTTPAuthEnvName);
        }
    }

    public function testConsulAppliesEnvHttpAuthWhenNotExplicit(): void
    {
        $this->setEnv(name: Consul::HTTPAuthEnvName, value: 'envuser:envpass');
        try {
            $options = $this->buildOptionsThroughConsul(new Config());
            self::assertSame(['envuser', 'envpass'], $options[GuzzleRequestOptions::AUTH]);
        } finally {
            $this->clearEnv(Consul::HTTPAuthEnvName);
        }
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

    private function setEnv(string $name, string $value): void
    {
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }

    private function clearEnv(string $name): void
    {
        putenv($name);
        unset($_ENV[$name], $_SERVER[$name]);
    }
}
