<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\ConfigEntry\RingHashConfig;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RingHashConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new RingHashConfig();
        self::assertTrue($c->getMinimumRingSize()->isZero());
        self::assertTrue($c->MinimumRingSize->isZero());
        self::assertTrue($c->getMaximumRingSize()->isZero());
        self::assertTrue($c->MaximumRingSize->isZero());
    }

    public function testConstructorWithParams(): void
    {
        $c = new RingHashConfig(MinimumRingSize: 1024, MaximumRingSize: 8192);
        self::assertSame('1024', (string)$c->getMinimumRingSize());
        self::assertSame('1024', (string)$c->MinimumRingSize);
        self::assertSame('8192', (string)$c->getMaximumRingSize());
        self::assertSame('8192', (string)$c->MaximumRingSize);
    }

    public function testConstructorAcceptsStringAndBigInteger(): void
    {
        $c = new RingHashConfig(
            MinimumRingSize: '18446744073709551615',
            MaximumRingSize: BigInteger::of('9223372036854775808'),
        );
        self::assertSame('18446744073709551615', (string)$c->MinimumRingSize);
        self::assertSame('9223372036854775808', (string)$c->MaximumRingSize);
    }

    public function testFluentSetters(): void
    {
        $c = new RingHashConfig();
        $result = $c->setMinimumRingSize(512)->setMaximumRingSize(4096);
        self::assertSame($c, $result);
        self::assertSame('512', (string)$c->getMinimumRingSize());
        self::assertSame('4096', (string)$c->getMaximumRingSize());
    }

    public function testSettersAcceptStringAndBigInteger(): void
    {
        $c = new RingHashConfig();
        $c->setMinimumRingSize('18446744073709551615');
        self::assertSame('18446744073709551615', (string)$c->MinimumRingSize);
        $c->setMaximumRingSize(BigInteger::of(42));
        self::assertSame('42', (string)$c->MaximumRingSize);
    }

    public function testSetterRejectsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new RingHashConfig())->setMinimumRingSize(-1);
    }

    public function testJsonSerializeOmitsZero(): void
    {
        $out = (new RingHashConfig())->jsonSerialize();
        self::assertObjectNotHasProperty('MinimumRingSize', $out);
        self::assertObjectNotHasProperty('MaximumRingSize', $out);

        $out = (new RingHashConfig(MinimumRingSize: 1, MaximumRingSize: 2))->jsonSerialize();
        self::assertInstanceOf(BigInteger::class, $out->MinimumRingSize);
        self::assertSame('1', (string)$out->MinimumRingSize);
        self::assertSame('2', (string)$out->MaximumRingSize);
    }

    public function testJsonUnserializeLargeValues(): void
    {
        $decoded = new \stdClass();
        $decoded->MinimumRingSize = '18446744073709551615';
        $decoded->maximum_ring_size = 10;
        $c = RingHashConfig::jsonUnserialize($decoded);
        self::assertSame('18446744073709551615', (string)$c->MinimumRingSize);
        self::assertSame('10', (string)$c->MaximumRingSize);
    }
}
