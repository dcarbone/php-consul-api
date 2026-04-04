<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

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
        self::assertSame(0, $c->getMinimumRingSize());
        self::assertSame(0, $c->getMaximumRingSize());
    }

    public function testConstructorWithParams(): void
    {
        $c = new RingHashConfig(MinimumRingSize: 1024, MaximumRingSize: 8192);
        self::assertSame(1024, $c->getMinimumRingSize());
        self::assertSame(8192, $c->getMaximumRingSize());
    }

    public function testFluentSetters(): void
    {
        $c = new RingHashConfig();
        $result = $c->setMinimumRingSize(512)->setMaximumRingSize(4096);
        self::assertSame($c, $result);
        self::assertSame(512, $c->getMinimumRingSize());
        self::assertSame(4096, $c->getMaximumRingSize());
    }

}
