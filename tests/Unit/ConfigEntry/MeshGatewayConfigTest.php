<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayMode;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class MeshGatewayConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new MeshGatewayConfig();
        self::assertSame(MeshGatewayMode::Default, $c->getMode());
    }

    public function testConstructorWithParams(): void
    {
        $c = new MeshGatewayConfig(mode: MeshGatewayMode::Local);
        self::assertSame(MeshGatewayMode::Local, $c->getMode());
    }

    public function testFluentSetters(): void
    {
        $c = new MeshGatewayConfig();
        $result = $c->setMode(MeshGatewayMode::Remote);
        self::assertSame($c, $result);
        self::assertSame(MeshGatewayMode::Remote, $c->getMode());
    }

    public function testConstructorWithEnumAsString(): void
    {
        $c = new MeshGatewayConfig(mode: 'local');
        self::assertSame(MeshGatewayMode::Local, $c->getMode());
    }

    public function testSetModeWithString(): void
    {
        $c = new MeshGatewayConfig();
        $c->setMode('remote');
        self::assertSame(MeshGatewayMode::Remote, $c->getMode());
    }
}
