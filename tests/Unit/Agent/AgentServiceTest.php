<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use Brick\Math\BigInteger;
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
        self::assertTrue($s->getCreateIndex()->isZero());
        self::assertTrue($s->getModifyIndex()->isZero());
    }

    public function testConstructorWithParams(): void
    {
        $s = new AgentService(
            Kind: ServiceKind::ConnectProxy,
            ID: 'svc-1',
            Service: 'web',
            Tags: ['v1'],
            Port: 8080,
            Address: '10.0.0.1',
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
        self::assertFalse(property_exists($out, 'CreateIndex'));
        self::assertFalse(property_exists($out, 'ModifyIndex'));
    }

    public function testUint64FieldsAcceptIntStringAndBigInteger(): void
    {
        $s = new AgentService(CreateIndex: 5, ModifyIndex: '18446744073709551615');
        self::assertSame('5', (string)$s->CreateIndex);
        self::assertSame('18446744073709551615', (string)$s->getModifyIndex());

        $s->setCreateIndex(BigInteger::of(9))->setModifyIndex(10);
        self::assertTrue($s->getCreateIndex()->isEqualTo(9));
        self::assertSame('10', (string)$s->ModifyIndex);

        $s->setCreateIndex('9223372036854775808');
        self::assertSame('9223372036854775808', (string)$s->CreateIndex);

        $out = $s->jsonSerialize();
        self::assertSame($s->CreateIndex, $out->CreateIndex);
        self::assertSame($s->ModifyIndex, $out->ModifyIndex);
    }

    public function testJsonUnserializeLargeIndexes(): void
    {
        $s = AgentService::jsonUnserialize(json_decode('{"ID":"svc","Service":"web","CreateIndex":18446744073709551615,"ModifyIndex":"42"}', flags: JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR));
        self::assertSame('18446744073709551615', (string)$s->CreateIndex);
        self::assertSame('42', (string)$s->ModifyIndex);
    }
}

