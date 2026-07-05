<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServiceConnectProxyConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new AgentServiceConnectProxyConfig();
        self::assertSame('', $p->getDestinationServiceName());
        self::assertSame('', $p->getDestinationServiceID());
        self::assertSame('', $p->getLocalServiceAddress());
        self::assertSame(0, $p->getLocalServicePort());
        self::assertSame([], $p->getEnvoyExtensions());
        self::assertSame([], $p->getUpstreams());
    }

    public function testConstructorWithParams(): void
    {
        $p = new AgentServiceConnectProxyConfig(
            DestinationServiceName: 'web',
            DestinationServiceID: 'web-1',
            LocalServiceAddress: '127.0.0.1',
            LocalServicePort: 8080,
        );
        self::assertSame('web', $p->getDestinationServiceName());
        self::assertSame('web-1', $p->getDestinationServiceID());
        self::assertSame('127.0.0.1', $p->getLocalServiceAddress());
        self::assertSame(8080, $p->getLocalServicePort());
    }

    public function testFluentSetters(): void
    {
        $p = new AgentServiceConnectProxyConfig();
        $result = $p->setDestinationServiceName('svc')->setDestinationServiceID('svc-1')
            ->setLocalServiceAddress('10.0.0.1')->setLocalServicePort(9090);
        self::assertSame($p, $result);
        self::assertSame('svc', $p->getDestinationServiceName());
    }

    public function testJsonSerialize(): void
    {
        $p = new AgentServiceConnectProxyConfig(DestinationServiceName: 'web', LocalServicePort: 80);
        $out = $p->jsonSerialize();
        self::assertSame('web', $out->DestinationServiceName);
        self::assertSame(80, $out->LocalServicePort);
    }
}

