<?php

namespace DCarbone\PHPConsulAPITests\Unit\PHPLib;

use DCarbone\PHPConsulAPI\Config;
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
}
