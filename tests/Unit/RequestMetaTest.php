<?php

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\PHPConsulAPI\RequestMeta;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

/**
 * @internal
 */
final class RequestMetaTest extends TestCase
{
    public function testConstructorWithParams(): void
    {
        $uri = new Uri('http://localhost:8500/v1/kv/test');
        $rm = new RequestMeta(method: 'GET', uri: $uri);
        self::assertSame('GET', $rm->getMethod());
        self::assertSame('GET', $rm->method);
        self::assertSame($uri, $rm->getUri());
        self::assertSame($uri, $rm->uri);
    }

    public function testFluentSetters(): void
    {
        $uri1 = new Uri('http://localhost:8500/v1/kv/a');
        $rm = new RequestMeta(method: 'GET', uri: $uri1);

        $uri2 = new Uri('http://localhost:8500/v1/kv/b');
        $result = $rm->setMethod('PUT')->setUri($uri2);

        self::assertSame($rm, $result);
        self::assertSame('PUT', $rm->getMethod());
        self::assertSame('PUT', $rm->method);
        self::assertSame($uri2, $rm->getUri());
        self::assertSame($uri2, $rm->uri);
    }

    public function testToString(): void
    {
        $uri = new Uri('http://localhost:8500/v1/agent/self');
        $rm = new RequestMeta(method: 'GET', uri: $uri);
        self::assertSame('GET http://localhost:8500/v1/agent/self', (string)$rm);
    }

    public function testToStringAfterSetters(): void
    {
        $uri1 = new Uri('http://localhost:8500/v1/kv/a');
        $rm = new RequestMeta(method: 'GET', uri: $uri1);

        $uri2 = new Uri('http://localhost:8500/v1/kv/b');
        $rm->setMethod('DELETE')->setUri($uri2);
        self::assertSame('DELETE http://localhost:8500/v1/kv/b', (string)$rm);
    }
}

