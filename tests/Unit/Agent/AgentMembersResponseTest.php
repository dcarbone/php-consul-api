<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentMember;
use DCarbone\PHPConsulAPI\Agent\AgentMembersResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentMembersResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new AgentMembersResponse();
        self::assertSame([], $r->getValue());
        self::assertSame([], $r->Members);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValue(): void
    {
        $obj = new \stdClass();
        $obj->Name = 'node-1';
        $obj->Addr = '10.0.0.1';
        $obj->Port = 8301;
        $obj->Tags = new \stdClass();
        $obj->Status = 1;
        $obj->ProtocolMin = 1;
        $obj->ProtocolMax = 5;
        $obj->ProtocolCur = 2;
        $obj->DelegateMin = 2;
        $obj->DelegateMax = 5;
        $obj->DelegateCur = 4;

        $r = new AgentMembersResponse();
        $r->unmarshalValue([$obj]);

        $members = $r->getValue();
        self::assertCount(1, $members);
        self::assertInstanceOf(AgentMember::class, $members[0]);
        self::assertSame('node-1', $members[0]->getName());
    }

    public function testOffsetAccess(): void
    {
        $r = new AgentMembersResponse();
        self::assertTrue(isset($r[0]));
        self::assertTrue(isset($r[1]));
        self::assertSame([], $r[0]);
        self::assertNull($r[1]);
    }
}

