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
            ->setVoter(true);
        self::assertSame($r, $result);
        self::assertSame('i', $r->ID);
        self::assertSame('n', $r->Node);
        self::assertSame('a', $r->Address);
        self::assertTrue($r->Leader);
        self::assertSame('2', $r->ProtocolVersion);
        self::assertTrue($r->Voter);
    }

    public function testJsonSerialize(): void
    {
        $r = new RaftServer(ID: 'x', Node: 'y', Leader: true);
        $out = $r->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertTrue($out->Leader);
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
        $r = RaftServer::jsonUnserialize($decoded);
        self::assertSame('r1', $r->getID());
        self::assertSame('n1', $r->getNode());
        self::assertTrue($r->isVoter());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new RaftServer(ID: 'rt', Node: 'n', Address: 'a', Leader: true, Voter: true);
        $restored = RaftServer::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->getID(), $restored->getID());
        self::assertSame($original->getNode(), $restored->getNode());
        self::assertSame($original->isLeader(), $restored->isLeader());
    }
}

