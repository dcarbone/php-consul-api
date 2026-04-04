<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IngressServiceConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\PassiveHealthCheck;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class IngressServiceConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new IngressServiceConfig();
        self::assertNull($c->getMaxConnections());
        self::assertNull($c->getMaxPendingRequests());
        self::assertNull($c->getMaxConcurrentRequests());
        self::assertNull($c->getPassiveHealthCheck());
    }

    public function testConstructorWithParams(): void
    {
        $phc = new PassiveHealthCheck(MaxFailures: 3);
        $c = new IngressServiceConfig(
            MaxConnections: 100,
            MaxPendingRequests: 50,
            MaxConcurrentRequests: 25,
            PassiveHealthCheck: $phc,
        );
        self::assertSame(100, $c->getMaxConnections());
        self::assertSame(50, $c->getMaxPendingRequests());
        self::assertSame(25, $c->getMaxConcurrentRequests());
        self::assertSame($phc, $c->getPassiveHealthCheck());
    }

    public function testFluentSetters(): void
    {
        $c = new IngressServiceConfig();
        $result = $c->setMaxConnections(10)
            ->setMaxPendingRequests(5)
            ->setMaxConcurrentRequests(3);
        self::assertSame($c, $result);
        self::assertSame(10, $c->getMaxConnections());
        self::assertSame(5, $c->getMaxPendingRequests());
        self::assertSame(3, $c->getMaxConcurrentRequests());
    }

}
