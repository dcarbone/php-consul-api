<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\ServiceKind;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceKindTest extends TestCase
{
    public function testCaseValues(): void
    {
        self::assertSame('', ServiceKind::Typical->value);
        self::assertSame('connect-proxy', ServiceKind::ConnectProxy->value);
        self::assertSame('mesh-gateway', ServiceKind::MeshGateway->value);
        self::assertSame('terminating-gateway', ServiceKind::TerminatingGateway->value);
        self::assertSame('ingress-gateway', ServiceKind::IngressGateway->value);
        self::assertSame('api-gateway', ServiceKind::APIGateway->value);
    }

    public function testFromString(): void
    {
        self::assertSame(ServiceKind::Typical, ServiceKind::from(''));
        self::assertSame(ServiceKind::ConnectProxy, ServiceKind::from('connect-proxy'));
        self::assertSame(ServiceKind::MeshGateway, ServiceKind::from('mesh-gateway'));
        self::assertSame(ServiceKind::TerminatingGateway, ServiceKind::from('terminating-gateway'));
        self::assertSame(ServiceKind::IngressGateway, ServiceKind::from('ingress-gateway'));
        self::assertSame(ServiceKind::APIGateway, ServiceKind::from('api-gateway'));
    }

    public function testTryFromValid(): void
    {
        self::assertSame(ServiceKind::ConnectProxy, ServiceKind::tryFrom('connect-proxy'));
    }

    public function testTryFromInvalid(): void
    {
        self::assertNull(ServiceKind::tryFrom('not-a-kind'));
    }

    public function testCaseCount(): void
    {
        self::assertCount(6, ServiceKind::cases());
    }
}

