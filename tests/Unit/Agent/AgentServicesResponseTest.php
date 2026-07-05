<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Agent\AgentServicesResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServicesResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new AgentServicesResponse();
        self::assertNull($r->getValue());
        self::assertNull($r->Services);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValueNull(): void
    {
        $r = new AgentServicesResponse();
        $r->unmarshalValue(null);
        self::assertNull($r->getValue());
    }

    public function testUnmarshalValueWithServices(): void
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

        $r = new AgentServicesResponse();
        $r->unmarshalValue(['web' => $obj]);

        $svcs = $r->getValue();
        self::assertIsArray($svcs);
        self::assertCount(1, $svcs);
        self::assertArrayHasKey('web', $svcs);
        self::assertInstanceOf(AgentService::class, $svcs['web']);
        self::assertSame('svc-1', $svcs['web']->getID());
    }

    public function testOffsetAccess(): void
    {
        $r = new AgentServicesResponse();
        self::assertTrue(isset($r[0]));
        self::assertTrue(isset($r[1]));
        self::assertNull($r[0]);
        self::assertNull($r[1]);
    }
}

