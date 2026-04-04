<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;
use DCarbone\PHPConsulAPI\Agent\ServiceKind;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServiceRegistrationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new AgentServiceRegistration();
        self::assertSame(ServiceKind::Typical, $r->getKind());
        self::assertSame('', $r->getID());
        self::assertSame('', $r->getName());
        self::assertSame(0, $r->getPort());
        self::assertSame('', $r->getAddress());
        self::assertFalse($r->isEnableTagOverride());
        self::assertSame('', $r->getNamespace());
        self::assertSame('', $r->getPartition());
    }

    public function testConstructorWithParams(): void
    {
        $r = new AgentServiceRegistration(
            Kind: ServiceKind::ConnectProxy,
            ID: 'svc-1',
            Name: 'web',
            Port: 8080,
            Address: '10.0.0.1',
            Tags: ['v1', 'primary'],
        );
        self::assertSame(ServiceKind::ConnectProxy, $r->getKind());
        self::assertSame('svc-1', $r->getID());
        self::assertSame('web', $r->getName());
        self::assertSame(8080, $r->getPort());
        self::assertSame(['v1', 'primary'], $r->getTags());
    }

    public function testConstructorWithStringKind(): void
    {
        $r = new AgentServiceRegistration(Kind: 'connect-proxy');
        self::assertSame(ServiceKind::ConnectProxy, $r->getKind());
    }

    public function testJsonSerialize(): void
    {
        $r = new AgentServiceRegistration(ID: 'svc', Name: 'web', Port: 80);
        $out = $r->jsonSerialize();
        self::assertSame('svc', $out->ID);
        self::assertSame('web', $out->Name);
        self::assertSame(80, $out->Port);
    }
}

