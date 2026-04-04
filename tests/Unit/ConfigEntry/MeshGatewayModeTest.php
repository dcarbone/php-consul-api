<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayMode;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MeshGatewayModeTest extends TestCase
{

    public function testDefaultCase(): void
    {
        $e = MeshGatewayMode::Default;
        self::assertSame('', $e->value);
    }

    public function testDefaultFromString(): void
    {
        $e = MeshGatewayMode::from('');
        self::assertSame(MeshGatewayMode::Default, $e);
    }

    public function testNoneCase(): void
    {
        $e = MeshGatewayMode::None;
        self::assertSame('none', $e->value);
    }

    public function testNoneFromString(): void
    {
        $e = MeshGatewayMode::from('none');
        self::assertSame(MeshGatewayMode::None, $e);
    }

    public function testLocalCase(): void
    {
        $e = MeshGatewayMode::Local;
        self::assertSame('local', $e->value);
    }

    public function testLocalFromString(): void
    {
        $e = MeshGatewayMode::from('local');
        self::assertSame(MeshGatewayMode::Local, $e);
    }

    public function testRemoteCase(): void
    {
        $e = MeshGatewayMode::Remote;
        self::assertSame('remote', $e->value);
    }

    public function testRemoteFromString(): void
    {
        $e = MeshGatewayMode::from('remote');
        self::assertSame(MeshGatewayMode::Remote, $e);
    }
}
