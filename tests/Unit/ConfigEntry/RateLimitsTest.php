<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\RateLimits;
use DCarbone\PHPConsulAPI\ConfigEntry\InstanceLevelRateLimits;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class RateLimitsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new RateLimits();
        self::assertInstanceOf(InstanceLevelRateLimits::class, $r->getInstanceLevel());
    }

    public function testConstructorWithParams(): void
    {
        $il = new InstanceLevelRateLimits(RequestsPerSecond: 100);
        $r = new RateLimits(instanceLevel: $il);
        self::assertSame($il, $r->getInstanceLevel());
    }

    public function testFluentSetters(): void
    {
        $il = new InstanceLevelRateLimits(RequestsPerSecond: 50);
        $r = new RateLimits();
        $result = $r->setInstanceLevel($il);
        self::assertSame($r, $result);
        self::assertSame($il, $r->getInstanceLevel());
    }

}
