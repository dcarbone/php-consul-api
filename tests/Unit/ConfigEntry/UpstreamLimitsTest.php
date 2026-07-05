<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\UpstreamLimits;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class UpstreamLimitsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $u = new UpstreamLimits();
        self::assertNull($u->getMaxConnections());
        self::assertNull($u->getMaxPendingRequests());
        self::assertNull($u->getMaxConcurrentRequests());
    }

    public function testConstructorWithParams(): void
    {
        $u = new UpstreamLimits(
            MaxConnections: 100,
            MaxPendingRequests: 50,
            MaxConcurrentRequests: 25,
        );
        self::assertSame(100, $u->getMaxConnections());
        self::assertSame(50, $u->getMaxPendingRequests());
        self::assertSame(25, $u->getMaxConcurrentRequests());
    }

    public function testFluentSetters(): void
    {
        $u = new UpstreamLimits();
        $result = $u->setMaxConnections(200)
            ->setMaxPendingRequests(100)
            ->setMaxConcurrentRequests(50);
        self::assertSame($u, $result);
        self::assertSame(200, $u->getMaxConnections());
        self::assertSame(100, $u->getMaxPendingRequests());
        self::assertSame(50, $u->getMaxConcurrentRequests());
    }

}
