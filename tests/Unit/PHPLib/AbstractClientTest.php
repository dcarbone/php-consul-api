<?php

namespace DCarbone\PHPConsulAPITests\Unit\PHPLib;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\PHPLib\AbstractClient;
use DCarbone\PHPConsulAPI\PHPLib\Request;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use PHPUnit\Framework\TestCase;

final class AbstractClientTest extends TestCase
{
    public function testExplicitContentTypeHeaderIsPreservedRegardlessOfCase(): void
    {
        $config = new Config();
        $client = new class($config) extends AbstractClient {
            /**
             * @return array<string,mixed>
             */
            public function buildGuzzleRequestOptions(Request $request): array
            {
                return $this->_buildGuzzleRequestOptions($request);
            }
        };

        foreach (['CONTENT-TYPE', 'Content-type'] as $headerName) {
            $request = new Request('POST', 'test', $config, ['payload' => 'value']);
            $request->header->set($headerName, 'application/custom');

            $options = $client->buildGuzzleRequestOptions($request);

            self::assertArrayNotHasKey(GuzzleRequestOptions::HEADERS, $options);
            self::assertSame('application/custom', $request->toPsrRequest()->getHeaderLine('Content-Type'));
        }
    }
}
