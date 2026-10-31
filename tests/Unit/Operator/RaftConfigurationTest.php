<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\Operator\RaftConfiguration;
use DCarbone\PHPConsulAPI\Operator\RaftServer;
use PHPUnit\Framework\TestCase;

use function DCarbone\PHPConsulAPI\PHPLib\encode_json;

/**
 * @internal
 */
final class RaftConfigurationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $rc = new RaftConfiguration();
        self::assertSame([], $rc->getServers());
        self::assertSame([], $rc->Servers);
        self::assertTrue($rc->getIndex()->isZero());
        self::assertTrue($rc->Index->isZero());
    }

    public function testConstructorWithServers(): void
    {
        $s1 = new RaftServer(ID: 'srv-1', Node: 'node-1');
        $s2 = new RaftServer(ID: 'srv-2', Node: 'node-2');

        $rc = new RaftConfiguration(Servers: [$s1, $s2], Index: 5);

        self::assertCount(2, $rc->getServers());
        self::assertCount(2, $rc->Servers);
        self::assertSame('srv-1', $rc->getServers()[0]->getID());
        self::assertSame('srv-2', $rc->getServers()[1]->getID());
        self::assertSame('5', (string)$rc->getIndex());
        self::assertSame('5', (string)$rc->Index);
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
        self::assertSame('42', (string)$rc->getIndex());
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
        self::assertSame($rc->Index, $out->Index);
        self::assertSame('7', (string)$out->Index);
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

        self::assertSame('15', (string)$rc->getIndex());
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
        $json = encode_json($serialized);
        self::assertIsString($json);
        $decoded = json_decode($json, flags: JSON_BIGINT_AS_STRING);
        self::assertInstanceOf(\stdClass::class, $decoded);

        $restored = RaftConfiguration::jsonUnserialize($decoded);

        self::assertTrue($original->getIndex()->isEqualTo($restored->getIndex()));
        self::assertCount(count($original->getServers()), $restored->getServers());
        self::assertSame($original->getServers()[0]->getID(), $restored->getServers()[0]->getID());
        self::assertSame($original->getServers()[1]->getID(), $restored->getServers()[1]->getID());
    }

    public function testIndexAcceptsIntStringAndBigInteger(): void
    {
        $rc = new RaftConfiguration(Index: '18446744073709551615');
        self::assertSame('18446744073709551615', (string)$rc->Index);

        $rc = new RaftConfiguration(Index: BigInteger::of(9));
        self::assertSame('9', (string)$rc->getIndex());

        $rc->setIndex('9223372036854775808');
        self::assertSame('9223372036854775808', (string)$rc->Index);

        $rc->setIndex(BigInteger::of(3));
        self::assertTrue($rc->Index->isEqualTo(3));
    }

    public function testJsonUnserializeLargeIndex(): void
    {
        $rc = RaftConfiguration::jsonUnserialize(json_decode('{"Servers":[{"ID":"a","LastIndex":18446744073709551615}],"Index":18446744073709551615}', flags: JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR));
        self::assertSame('18446744073709551615', (string)$rc->Index);
        self::assertSame('18446744073709551615', (string)$rc->getServers()[0]->LastIndex);

        self::assertSame(
            '{"Servers":[{"ID":"a","Node":"","Address":"","Leader":false,"ProtocolVersion":"","Voter":false,"LastIndex":18446744073709551615}],"Index":18446744073709551615}',
            encode_json($rc),
        );
    }
}

