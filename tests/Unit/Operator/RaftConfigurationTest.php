<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\RaftConfiguration;
use DCarbone\PHPConsulAPI\Operator\RaftServer;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RaftConfigurationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $rc = new RaftConfiguration();
        self::assertSame([], $rc->getServers());
        self::assertSame(0, $rc->getIndex());
    }

    public function testConstructorWithServers(): void
    {
        $s1 = new RaftServer(ID: 'srv-1', Node: 'node-1');
        $s2 = new RaftServer(ID: 'srv-2', Node: 'node-2');

        $rc = new RaftConfiguration(Servers: [$s1, $s2], Index: 5);

        self::assertCount(2, $rc->getServers());
        self::assertSame('srv-1', $rc->getServers()[0]->getID());
        self::assertSame('srv-2', $rc->getServers()[1]->getID());
        self::assertSame(5, $rc->getIndex());
    }

    public function testVariadicSetServersReplacesExisting(): void
    {
        $rc = new RaftConfiguration(Servers: [new RaftServer(ID: 'old')]);
        $rc->setServers(new RaftServer(ID: 'new-1'), new RaftServer(ID: 'new-2'));

        self::assertCount(2, $rc->getServers());
        self::assertSame('new-1', $rc->getServers()[0]->getID());
        self::assertSame('new-2', $rc->getServers()[1]->getID());
    }

    public function testVariadicSetServersWithNoArgsClearsArray(): void
    {
        $rc = new RaftConfiguration(Servers: [new RaftServer(ID: 'x')]);
        $rc->setServers();

        self::assertSame([], $rc->getServers());
    }

    public function testFluentSetters(): void
    {
        $rc = new RaftConfiguration();
        $result = $rc
            ->setServers(new RaftServer(ID: 'a'))
            ->setIndex(42);

        self::assertSame($rc, $result);
        self::assertSame(42, $rc->getIndex());
        self::assertCount(1, $rc->getServers());
    }

    public function testJsonSerialize(): void
    {
        $rc = new RaftConfiguration(
            Servers: [new RaftServer(ID: 'srv-a', Node: 'n-a', Leader: true, Voter: true)],
            Index: 7,
        );

        $out = $rc->jsonSerialize();

        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(7, $out->Index);
        self::assertIsArray($out->Servers);
        self::assertCount(1, $out->Servers);
    }

    public function testJsonUnserialize(): void
    {
        $serverObj = new \stdClass();
        $serverObj->ID = 'srv-z';
        $serverObj->Node = 'node-z';
        $serverObj->Address = '10.0.0.1:8300';
        $serverObj->Leader = true;
        $serverObj->ProtocolVersion = '3';
        $serverObj->Voter = true;

        $decoded = new \stdClass();
        $decoded->Servers = [$serverObj];
        $decoded->Index = 15;

        $rc = RaftConfiguration::jsonUnserialize($decoded);

        self::assertSame(15, $rc->getIndex());
        self::assertCount(1, $rc->getServers());

        $server = $rc->getServers()[0];
        self::assertInstanceOf(RaftServer::class, $server);
        self::assertSame('srv-z', $server->getID());
        self::assertSame('node-z', $server->getNode());
        self::assertTrue($server->isLeader());
        self::assertTrue($server->isVoter());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new RaftConfiguration(
            Servers: [
                new RaftServer(ID: 'a', Node: 'n-a', Leader: true, Voter: true),
                new RaftServer(ID: 'b', Node: 'n-b', Leader: false, Voter: true),
            ],
            Index: 42,
        );

        $serialized = $original->jsonSerialize();

        // Simulate JSON encode/decode cycle
        $json = json_encode($serialized);
        self::assertIsString($json);
        $decoded = json_decode($json);
        self::assertInstanceOf(\stdClass::class, $decoded);

        $restored = RaftConfiguration::jsonUnserialize($decoded);

        self::assertSame($original->getIndex(), $restored->getIndex());
        self::assertCount(count($original->getServers()), $restored->getServers());
        self::assertSame($original->getServers()[0]->getID(), $restored->getServers()[0]->getID());
        self::assertSame($original->getServers()[1]->getID(), $restored->getServers()[1]->getID());
    }
}

