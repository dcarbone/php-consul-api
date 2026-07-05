<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouterConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRoute;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteDestination;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceRouterConfigEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new ServiceRouterConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getPartition());
        self::assertSame([], $e->getRoutes());
    }

    public function testConstructorWithParams(): void
    {
        $route = new ServiceRoute(Destination: new ServiceRouteDestination(Service: 'web'));
        $e = new ServiceRouterConfigEntry(
            Kind: 'service-router',
            Name: 'web',
            Partition: 'pt',
            Routes: [$route],
        );
        self::assertSame('service-router', $e->getKind());
        self::assertSame('web', $e->getName());
        self::assertSame('pt', $e->getPartition());
        self::assertCount(1, $e->getRoutes());
    }

    public function testFluentSetters(): void
    {
        $route = new ServiceRoute(Destination: new ServiceRouteDestination(Service: 'api'));
        $e = new ServiceRouterConfigEntry();
        $result = $e->setKind('service-router')
            ->setName('api')
            ->setPartition('pt')
            ->setRoutes($route);
        self::assertSame($e, $result);
        self::assertSame('service-router', $e->getKind());
        self::assertSame('api', $e->getName());
        self::assertSame('pt', $e->getPartition());
        self::assertCount(1, $e->getRoutes());
    }

}
