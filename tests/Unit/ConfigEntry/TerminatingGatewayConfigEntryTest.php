<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\TerminatingGatewayConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\LinkedService;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class TerminatingGatewayConfigEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new TerminatingGatewayConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame([], $e->getServices());
    }

    public function testConstructorWithParams(): void
    {
        $svc = new LinkedService(Name: 'external-db');
        $e = new TerminatingGatewayConfigEntry(
            Kind: 'terminating-gateway',
            Name: 'tgw',
            Services: [$svc],
        );
        self::assertSame('terminating-gateway', $e->getKind());
        self::assertSame('tgw', $e->getName());
        self::assertCount(1, $e->getServices());
    }

    public function testFluentSetters(): void
    {
        $svc = new LinkedService(Name: 'ext');
        $e = new TerminatingGatewayConfigEntry();
        $result = $e->setKind('terminating-gateway')
            ->setName('tgw')
            ->setServices($svc);
        self::assertSame($e, $result);
        self::assertSame('terminating-gateway', $e->getKind());
        self::assertSame('tgw', $e->getName());
        self::assertCount(1, $e->getServices());
    }

}
