<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\OIDCClientAssertionKey;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class OIDCClientAssertionKeyTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $k = new OIDCClientAssertionKey();
        self::assertSame('', $k->getPemKey());
    }

    public function testConstructorWithParams(): void
    {
        $k = new OIDCClientAssertionKey(PemKey: 'pem-data');
        self::assertSame('pem-data', $k->getPemKey());
        self::assertSame('pem-data', $k->PemKey);
    }

    public function testFluentSetters(): void
    {
        $k = new OIDCClientAssertionKey();
        $result = $k->setPemKey('my-key');
        self::assertSame($k, $result);
        self::assertSame('my-key', $k->getPemKey());
    }

    public function testJsonSerialize(): void
    {
        $k = new OIDCClientAssertionKey(PemKey: 'test-pem');
        $out = $k->jsonSerialize();
        self::assertSame('test-pem', $out->PemKey);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->PemKey = 'decoded-pem';
        $k = OIDCClientAssertionKey::jsonUnserialize($d);
        self::assertSame('decoded-pem', $k->getPemKey());
    }
}

