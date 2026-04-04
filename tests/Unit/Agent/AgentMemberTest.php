<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentMember;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentMemberTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new AgentMember();
        self::assertSame('', $m->getName());
        self::assertSame('', $m->getAddr());
        self::assertSame(0, $m->getPort());
        self::assertSame([], $m->getTags());
        self::assertSame(0, $m->getStatus());
        self::assertSame(0, $m->getProtocolMin());
        self::assertSame(0, $m->getProtocolMax());
        self::assertSame(0, $m->getProtocolCur());
        self::assertSame(0, $m->getDelegateMin());
        self::assertSame(0, $m->getDelegateMax());
        self::assertSame(0, $m->getDelegateCur());
    }

    public function testConstructorWithParams(): void
    {
        $m = new AgentMember(Name: 'node1', Addr: '127.0.0.1', Port: 8301, Tags: ['role' => 'consul'], Status: 1);
        self::assertSame('node1', $m->getName());
        self::assertSame('127.0.0.1', $m->getAddr());
        self::assertSame(8301, $m->getPort());
        self::assertSame(['role' => 'consul'], $m->getTags());
        self::assertSame(1, $m->getStatus());
    }

    public function testJsonSerialize(): void
    {
        $m = new AgentMember(Name: 'n', Addr: 'a', Port: 1);
        $out = $m->jsonSerialize();
        self::assertSame('n', $out->Name);
        self::assertSame('a', $out->Addr);
        self::assertSame(1, $out->Port);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Name = 'node2';
        $d->Addr = '10.0.0.1';
        $d->Port = 8301;
        $d->Tags = new \stdClass();
        $d->Tags->role = 'consul';
        $d->Status = 1;
        $d->ProtocolMin = 1;
        $d->ProtocolMax = 5;
        $d->ProtocolCur = 3;
        $d->DelegateMin = 2;
        $d->DelegateMax = 5;
        $d->DelegateCur = 4;
        $m = AgentMember::jsonUnserialize($d);
        self::assertSame('node2', $m->getName());
        self::assertSame(8301, $m->getPort());
    }
}

