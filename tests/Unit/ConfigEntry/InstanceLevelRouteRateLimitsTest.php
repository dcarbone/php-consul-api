<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\InstanceLevelRouteRateLimits;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class InstanceLevelRouteRateLimitsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new InstanceLevelRouteRateLimits();
        self::assertSame('', $r->getPathExact());
        self::assertSame('', $r->getPathPrefix());
        self::assertSame('', $r->getPathRegex());
        self::assertSame(0, $r->getRequestsPerSecond());
        self::assertSame(0, $r->getRequestsMaxBurst());
    }

    public function testConstructorWithParams(): void
    {
        $r = new InstanceLevelRouteRateLimits(
            PathExact: '/api/v1',
            PathPrefix: '/api',
            PathRegex: '/api/.*',
            RequestsPerSecond: 50,
            RequestsMaxBurst: 100,
        );
        self::assertSame('/api/v1', $r->getPathExact());
        self::assertSame('/api', $r->getPathPrefix());
        self::assertSame('/api/.*', $r->getPathRegex());
        self::assertSame(50, $r->getRequestsPerSecond());
        self::assertSame(100, $r->getRequestsMaxBurst());
    }

    public function testFluentSetters(): void
    {
        $r = new InstanceLevelRouteRateLimits();
        $result = $r->setPathExact('/exact')
            ->setPathPrefix('/prefix')
            ->setPathRegex('/re.*')
            ->setRequestsPerSecond(10)
            ->setRequestsMaxBurst(20);
        self::assertSame($r, $result);
        self::assertSame('/exact', $r->getPathExact());
        self::assertSame('/prefix', $r->getPathPrefix());
        self::assertSame('/re.*', $r->getPathRegex());
        self::assertSame(10, $r->getRequestsPerSecond());
        self::assertSame(20, $r->getRequestsMaxBurst());
    }

}
