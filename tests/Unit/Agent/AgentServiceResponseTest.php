<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Agent\AgentServiceResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServiceResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new AgentServiceResponse();
        self::assertNull($r->getValue());
        self::assertNull($r->Service);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValueNull(): void
    {
        $r = new AgentServiceResponse();
        $r->unmarshalValue(null);
        self::assertNull($r->getValue());
    }

    public function testUnmarshalValueWithService(): void
    {
        $obj = new \stdClass();
        $obj->Kind = '';
        $obj->ID = 'svc-1';
        $obj->Service = 'web';
        $obj->Port = 8080;
        $obj->Address = '10.0.0.1';
        $obj->Tags = [];
        $obj->Meta = new \stdClass();
        $obj->CreateIndex = 1;
        $obj->ModifyIndex = 2;

        $r = new AgentServiceResponse();
        $r->unmarshalValue($obj);

        $svc = $r->getValue();
        self::assertInstanceOf(AgentService::class, $svc);
        self::assertSame('svc-1', $svc->getID());
        self::assertSame('web', $svc->getService());
        self::assertSame(8080, $svc->getPort());
    }

    public function testOffsetAccess(): void
    {
        $r = new AgentServiceResponse();
        self::assertTrue(isset($r[0]));
        self::assertTrue(isset($r[1]));
        self::assertNull($r[0]);
        self::assertNull($r[1]);
    }
}

