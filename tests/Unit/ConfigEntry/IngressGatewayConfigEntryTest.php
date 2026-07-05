<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IngressGatewayConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\IngressListener;
use DCarbone\PHPConsulAPI\ConfigEntry\IngressServiceConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class IngressGatewayConfigEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new IngressGatewayConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getPartition());
        self::assertSame([], $e->getListeners());
        self::assertNull($e->getDefaults());
    }

    public function testConstructorWithParams(): void
    {
        $listener = new IngressListener(Port: 8080, Protocol: 'http');
        $defaults = new IngressServiceConfig(MaxConnections: 100);
        $e = new IngressGatewayConfigEntry(
            Kind: 'ingress-gateway',
            Name: 'my-gateway',
            Partition: 'default',
            Listeners: [$listener],
            Defaults: $defaults,
        );
        self::assertSame('ingress-gateway', $e->getKind());
        self::assertSame('my-gateway', $e->getName());
        self::assertSame('default', $e->getPartition());
        self::assertCount(1, $e->getListeners());
        self::assertSame($defaults, $e->getDefaults());
    }

    public function testFluentSetters(): void
    {
        $listener = new IngressListener(Port: 443);
        $e = new IngressGatewayConfigEntry();
        $result = $e->setKind('ingress-gateway')
            ->setName('gw')
            ->setPartition('pt')
            ->setListeners($listener);
        self::assertSame($e, $result);
        self::assertSame('ingress-gateway', $e->getKind());
        self::assertSame('gw', $e->getName());
        self::assertSame('pt', $e->getPartition());
        self::assertCount(1, $e->getListeners());
    }

}
