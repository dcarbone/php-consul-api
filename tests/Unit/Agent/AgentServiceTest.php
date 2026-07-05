<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Agent\ServiceKind;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServiceTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new AgentService();
        self::assertSame(ServiceKind::Typical, $s->getKind());
        self::assertSame('', $s->getID());
        self::assertSame('', $s->getService());
        self::assertSame(0, $s->getPort());
        self::assertSame('', $s->getAddress());
        self::assertSame(0, $s->getCreateIndex());
        self::assertSame(0, $s->getModifyIndex());
    }

    public function testConstructorWithParams(): void
    {
        $s = new AgentService(
            Kind: ServiceKind::ConnectProxy,
            ID: 'svc-1',
            Service: 'web',
            Port: 8080,
            Address: '10.0.0.1',
            Tags: ['v1'],
        );
        self::assertSame(ServiceKind::ConnectProxy, $s->getKind());
        self::assertSame('svc-1', $s->getID());
        self::assertSame('web', $s->getService());
        self::assertSame(8080, $s->getPort());
        self::assertSame(['v1'], $s->getTags());
    }

    public function testConstructorWithStringKind(): void
    {
        $s = new AgentService(Kind: 'connect-proxy');
        self::assertSame(ServiceKind::ConnectProxy, $s->getKind());
    }

    public function testJsonSerialize(): void
    {
        $s = new AgentService(ID: 'svc', Service: 'web', Port: 80);
        $out = $s->jsonSerialize();
        self::assertSame('svc', $out->ID);
        self::assertSame('web', $out->Service);
        self::assertSame(80, $out->Port);
    }
}

