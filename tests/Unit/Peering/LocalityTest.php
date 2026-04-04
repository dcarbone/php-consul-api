<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Peering;

use DCarbone\PHPConsulAPI\Peering\Locality;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LocalityTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $l = new Locality();
        self::assertSame('', $l->getRegion());
        self::assertSame('', $l->getZone());
    }

    public function testConstructorWithValues(): void
    {
        $l = new Locality(Region: 'us-east-1', Zone: 'us-east-1a');
        self::assertSame('us-east-1', $l->getRegion());
        self::assertSame('us-east-1a', $l->getZone());
    }

    public function testFluentSetters(): void
    {
        $l = new Locality();
        $result = $l->setRegion('eu-west-1')->setZone('eu-west-1b');
        self::assertSame($l, $result);
        self::assertSame('eu-west-1', $l->getRegion());
        self::assertSame('eu-west-1b', $l->getZone());
    }

    public function testJsonSerialize(): void
    {
        $l = new Locality(Region: 'us-west-2', Zone: 'us-west-2c');
        $out = $l->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('us-west-2', $out->Region);
        self::assertSame('us-west-2c', $out->Zone);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->Region = 'ap-south-1';
        $decoded->Zone = 'ap-south-1a';
        $l = Locality::jsonUnserialize($decoded);
        self::assertSame('ap-south-1', $l->getRegion());
        self::assertSame('ap-south-1a', $l->getZone());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new Locality(Region: 'us-east-1', Zone: 'us-east-1b');
        $restored = Locality::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->getRegion(), $restored->getRegion());
        self::assertSame($original->getZone(), $restored->getZone());
    }
}

