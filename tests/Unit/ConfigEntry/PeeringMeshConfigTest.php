<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\PeeringMeshConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class PeeringMeshConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new PeeringMeshConfig();
        self::assertFalse($c->isPeerThroughMeshGateways());
    }

    public function testConstructorWithParams(): void
    {
        $c = new PeeringMeshConfig(PeerThroughMeshGateways: true);
        self::assertTrue($c->isPeerThroughMeshGateways());
    }

    public function testFluentSetters(): void
    {
        $c = new PeeringMeshConfig();
        $result = $c->setPeerThroughMeshGateways(true);
        self::assertSame($c, $result);
        self::assertTrue($c->isPeerThroughMeshGateways());
    }

}
