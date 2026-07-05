<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentHealthServicesResponse;
use DCarbone\PHPConsulAPI\Agent\AgentServiceChecksInfo;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentHealthServicesResponseTest extends TestCase
{
    public function testConstructorEmptyCheckInfos(): void
    {
        $r = new AgentHealthServicesResponse('passing', [], null);
        self::assertSame('passing', $r->getAggregatedStatus());
        self::assertSame('passing', $r->AggregatedStatus);
        self::assertSame([], $r->getAgentServiceChecksInfos());
        self::assertNull($r->Err);
    }

    public function testConstructorWithCheckInfos(): void
    {
        $info = new \stdClass();
        $info->AggregatedStatus = 'passing';
        $info->Service = new \stdClass();
        $info->Service->ID = 'svc-1';
        $info->Service->Service = 'web';
        $info->Service->Port = 80;
        $info->Service->Address = '';
        $info->Service->Tags = [];
        $info->Service->Meta = new \stdClass();
        $info->Service->Kind = '';
        $info->Service->CreateIndex = 0;
        $info->Service->ModifyIndex = 0;
        $info->Checks = [];

        $r = new AgentHealthServicesResponse('passing', [$info], null);
        self::assertCount(1, $r->getAgentServiceChecksInfos());
        self::assertInstanceOf(AgentServiceChecksInfo::class, $r->getAgentServiceChecksInfos()[0]);
    }

    public function testConstructorWithError(): void
    {
        $err = new Error('fail');
        $r = new AgentHealthServicesResponse('critical', [], $err);
        self::assertSame($err, $r->Err);
    }

    public function testOffsetAccess(): void
    {
        $r = new AgentHealthServicesResponse('passing', [], null);
        self::assertTrue(isset($r[0]));
        self::assertTrue(isset($r[1]));
        self::assertTrue(isset($r[2]));
        self::assertFalse(isset($r[3]));
        self::assertSame('passing', $r[0]);
        self::assertSame([], $r[1]);
        self::assertNull($r[2]);
    }
}

