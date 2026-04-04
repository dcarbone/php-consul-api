<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\GatewayServiceTLSConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\GatewayTLSSDSConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class GatewayServiceTLSConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new GatewayServiceTLSConfig();
        self::assertNull($c->getSDS());
    }

    public function testConstructorWithParams(): void
    {
        $sds = new GatewayTLSSDSConfig(ClusterName: 'c', CertResource: 'r');
        $c = new GatewayServiceTLSConfig(SDS: $sds);
        self::assertSame($sds, $c->getSDS());
    }

    public function testFluentSetters(): void
    {
        $sds = new GatewayTLSSDSConfig(ClusterName: 'c');
        $c = new GatewayServiceTLSConfig();
        $result = $c->setSDS($sds);
        self::assertSame($c, $result);
        self::assertSame($sds, $c->getSDS());
    }

}
