<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\InstanceLevelRateLimits;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class InstanceLevelRateLimitsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $i = new InstanceLevelRateLimits();
        self::assertSame(0, $i->getRequestsPerSecond());
        self::assertSame(0, $i->getRequestsMaxBurst());
        self::assertSame([], $i->getRoutes());
    }

    public function testConstructorWithParams(): void
    {
        $i = new InstanceLevelRateLimits(
            RequestsPerSecond: 100,
            RequestsMaxBurst: 200,
        );
        self::assertSame(100, $i->getRequestsPerSecond());
        self::assertSame(200, $i->getRequestsMaxBurst());
    }

    public function testFluentSetters(): void
    {
        $i = new InstanceLevelRateLimits();
        $result = $i->setRequestsPerSecond(50)->setRequestsMaxBurst(100);
        self::assertSame($i, $result);
        self::assertSame(50, $i->getRequestsPerSecond());
        self::assertSame(100, $i->getRequestsMaxBurst());
    }

}
