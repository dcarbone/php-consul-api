<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\GatewayTLSConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\GatewayTLSSDSConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class GatewayTLSConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new GatewayTLSConfig();
        self::assertFalse($c->isEnabled());
        self::assertNull($c->getSDS());
        self::assertSame('', $c->getTLSMinVersion());
        self::assertSame('', $c->getTLSMaxVersion());
        self::assertSame([], $c->getCipherSuites());
    }

    public function testConstructorWithParams(): void
    {
        $sds = new GatewayTLSSDSConfig(ClusterName: 'c');
        $c = new GatewayTLSConfig(
            Enabled: true,
            SDS: $sds,
            TLSMinVersion: 'TLSv1_2',
            TLSMaxVersion: 'TLSv1_3',
            CipherSuites: ['suite1'],
        );
        self::assertTrue($c->isEnabled());
        self::assertSame($sds, $c->getSDS());
        self::assertSame('TLSv1_2', $c->getTLSMinVersion());
        self::assertSame('TLSv1_3', $c->getTLSMaxVersion());
        self::assertSame(['suite1'], $c->getCipherSuites());
    }

    public function testFluentSetters(): void
    {
        $c = new GatewayTLSConfig();
        $result = $c->setEnabled(true)
            ->setTLSMinVersion('TLSv1_2')
            ->setTLSMaxVersion('TLSv1_3')
            ->setCipherSuites('s1', 's2');
        self::assertSame($c, $result);
        self::assertTrue($c->isEnabled());
        self::assertSame('TLSv1_2', $c->getTLSMinVersion());
        self::assertSame('TLSv1_3', $c->getTLSMaxVersion());
        self::assertSame(['s1', 's2'], $c->getCipherSuites());
    }

}
