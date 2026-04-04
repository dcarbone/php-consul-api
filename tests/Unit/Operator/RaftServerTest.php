<?php

declare(strict_types=1);

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
        self::assertSame('', $r->getNode());
        self::assertSame('', $r->getAddress());
        self::assertFalse($r->isLeader());
        self::assertSame('', $r->getProtocolVersion());
        self::assertFalse($r->isVoter());
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
        self::assertSame('node-1', $r->getNode());
        self::assertSame('10.0.0.1:8300', $r->getAddress());
        self::assertTrue($r->isLeader());
        self::assertSame('3', $r->getProtocolVersion());
        self::assertTrue($r->isVoter());
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

