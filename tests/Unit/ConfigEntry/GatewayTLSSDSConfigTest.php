<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\GatewayTLSSDSConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class GatewayTLSSDSConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new GatewayTLSSDSConfig();
        self::assertSame('', $c->getClusterName());
        self::assertSame('', $c->getCertResource());
    }

    public function testConstructorWithParams(): void
    {
        $c = new GatewayTLSSDSConfig(ClusterName: 'cluster', CertResource: 'cert');
        self::assertSame('cluster', $c->getClusterName());
        self::assertSame('cert', $c->getCertResource());
    }

    public function testFluentSetters(): void
    {
        $c = new GatewayTLSSDSConfig();
        $result = $c->setClusterName('cl')->setCertResource('cr');
        self::assertSame($c, $result);
        self::assertSame('cl', $c->getClusterName());
        self::assertSame('cr', $c->getCertResource());
    }

}
