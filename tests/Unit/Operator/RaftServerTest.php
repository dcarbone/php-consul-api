<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\RaftServer;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RaftServerTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new RaftServer();
        self::assertSame('', $r->getID());
        self::assertSame('', $r->ID);
        self::assertSame('', $r->getNode());
        self::assertSame('', $r->Node);
        self::assertSame('', $r->getAddress());
        self::assertSame('', $r->Address);
        self::assertFalse($r->isLeader());
        self::assertFalse($r->Leader);
        self::assertSame('', $r->getProtocolVersion());
        self::assertSame('', $r->ProtocolVersion);
        self::assertFalse($r->isVoter());
        self::assertFalse($r->Voter);
        self::assertSame(0, $r->getLastIndex());
        self::assertSame(0, $r->LastIndex);
    }

    public function testConstructorWithValues(): void
    {
        $r = new RaftServer(
            ID: 'id-1',
            Node: 'node-1',
            Address: '10.0.0.1:8300',
            Leader: true,
            ProtocolVersion: '3',
            Voter: true,
            LastIndex: 42,
        );
        self::assertSame('id-1', $r->getID());
        self::assertSame('id-1', $r->ID);
        self::assertSame('node-1', $r->getNode());
        self::assertSame('node-1', $r->Node);
        self::assertSame('10.0.0.1:8300', $r->getAddress());
        self::assertSame('10.0.0.1:8300', $r->Address);
        self::assertTrue($r->isLeader());
        self::assertTrue($r->Leader);
        self::assertSame('3', $r->getProtocolVersion());
        self::assertSame('3', $r->ProtocolVersion);
        self::assertTrue($r->isVoter());
        self::assertTrue($r->Voter);
        self::assertSame(42, $r->getLastIndex());
        self::assertSame(42, $r->LastIndex);
    }

    public function testFluentSetters(): void
    {
        $r = new RaftServer();
        $result = $r
            ->setID('i')
            ->setNode('n')
            ->setAddress('a')
            ->setLeader(true)
            ->setProtocolVersion('2')
            ->setVoter(true)
            ->setLastIndex(99);
        self::assertSame($r, $result);
        self::assertSame('i', $r->ID);
        self::assertSame('n', $r->Node);
        self::assertSame('a', $r->Address);
        self::assertTrue($r->Leader);
        self::assertSame('2', $r->ProtocolVersion);
        self::assertTrue($r->Voter);
        self::assertSame(99, $r->LastIndex);
    }

    public function testJsonSerialize(): void
    {
        $r = new RaftServer(ID: 'x', Node: 'y', Leader: true, LastIndex: 55);
        $out = $r->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertTrue($out->Leader);
        self::assertSame(55, $out->LastIndex);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'r1';
        $decoded->Node = 'n1';
        $decoded->Address = '10.0.0.1:8300';
        $decoded->Leader = false;
        $decoded->ProtocolVersion = '3';
        $decoded->Voter = true;
        $decoded->LastIndex = 123;
        $r = RaftServer::jsonUnserialize($decoded);
        self::assertSame('r1', $r->getID());
        self::assertSame('n1', $r->getNode());
        self::assertTrue($r->isVoter());
        self::assertSame(123, $r->getLastIndex());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new RaftServer(ID: 'rt', Node: 'n', Address: 'a', Leader: true, Voter: true, LastIndex: 77);
        $restored = RaftServer::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->getID(), $restored->getID());
        self::assertSame($original->getNode(), $restored->getNode());
        self::assertSame($original->isLeader(), $restored->isLeader());
        self::assertSame($original->getLastIndex(), $restored->getLastIndex());
    }
}

