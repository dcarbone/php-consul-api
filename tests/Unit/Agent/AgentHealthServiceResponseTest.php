<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentHealthServiceResponse;
use DCarbone\PHPConsulAPI\Agent\AgentServiceChecksInfo;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentHealthServiceResponseTest extends TestCase
{
    public function testConstructorWithNullChecksInfo(): void
    {
        $r = new AgentHealthServiceResponse('passing', null, null);
        self::assertSame('passing', $r->getAggregatedStatus());
        self::assertSame('passing', $r->AggregatedStatus);
        self::assertNull($r->getAgentServiceChecksInfos());
        self::assertNull($r->AgentServiceChecksInfo);
        self::assertNull($r->Err);
    }

    public function testConstructorWithChecksInfo(): void
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

        $r = new AgentHealthServiceResponse('passing', $info, null);
        self::assertSame('passing', $r->getAggregatedStatus());
        self::assertInstanceOf(AgentServiceChecksInfo::class, $r->getAgentServiceChecksInfos());
    }

    public function testConstructorWithError(): void
    {
        $err = new Error('something failed');
        $r = new AgentHealthServiceResponse('critical', null, $err);
        self::assertSame('critical', $r->getAggregatedStatus());
        self::assertSame($err, $r->Err);
    }

    public function testOffsetAccess(): void
    {
        $r = new AgentHealthServiceResponse('passing', null, null);
        self::assertTrue(isset($r[0]));
        self::assertTrue(isset($r[1]));
        self::assertTrue(isset($r[2]));
        self::assertFalse(isset($r[3]));
        self::assertSame('passing', $r[0]);
        self::assertNull($r[1]);
        self::assertNull($r[2]);
    }
}

