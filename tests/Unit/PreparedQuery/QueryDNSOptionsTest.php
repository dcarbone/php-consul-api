<?php

namespace DCarbone\PHPConsulAPITests\Unit\PreparedQuery;

use DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class QueryDNSOptionsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $d = new QueryDNSOptions();
        self::assertSame('', $d->getTTL());
        self::assertSame('', $d->TTL);
    }

    public function testConstructorWithValues(): void
    {
        $d = new QueryDNSOptions(TTL: '10s');
        self::assertSame('10s', $d->getTTL());
        self::assertSame('10s', $d->TTL);
    }

    public function testFluentSetters(): void
    {
        $d = new QueryDNSOptions();
        $result = $d->setTTL('30s');
        self::assertSame($d, $result);
        self::assertSame('30s', $d->getTTL());
        self::assertSame('30s', $d->TTL);
    }

    public function testJsonSerialize(): void
    {
        $d = new QueryDNSOptions(TTL: '5s');
        $out = $d->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('5s', $out->TTL);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->TTL = '15s';
        $d = QueryDNSOptions::jsonUnserialize($decoded);
        self::assertSame('15s', $d->getTTL());
        self::assertSame('15s', $d->TTL);
    }

    public function testJsonRoundTrip(): void
    {
        $original = new QueryDNSOptions(TTL: '60s');
        $restored = QueryDNSOptions::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->getTTL(), $restored->getTTL());
    }
}

