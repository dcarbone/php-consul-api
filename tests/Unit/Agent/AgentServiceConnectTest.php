<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentServiceConnect;
use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServiceConnectTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new AgentServiceConnect();
        self::assertFalse($c->isNative());
        self::assertNull($c->getSidecarService());
    }

    public function testConstructorWithParams(): void
    {
        $sidecar = new AgentServiceRegistration(ID: 'sidecar', Name: 'web-sidecar');
        $c = new AgentServiceConnect(Native: true, SidecarService: $sidecar);
        self::assertTrue($c->isNative());
        self::assertSame($sidecar, $c->getSidecarService());
    }

    public function testJsonSerialize(): void
    {
        $c = new AgentServiceConnect(Native: true);
        $out = $c->jsonSerialize();
        self::assertTrue($out->Native);
    }
}

