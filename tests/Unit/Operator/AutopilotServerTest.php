<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\Operator\AutopilotServer;
use DCarbone\PHPConsulAPI\Operator\AutopilotServerStatus;
use DCarbone\PHPConsulAPI\Operator\AutopilotServerType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AutopilotServerTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new AutopilotServer();
        self::assertSame('', $s->getID());
        self::assertSame('', $s->ID);
        self::assertSame('', $s->getName());
        self::assertSame('', $s->Name);
        self::assertSame('', $s->getAddress());
        self::assertSame('', $s->Address);
        self::assertSame('', $s->getNodeStatus());
        self::assertSame('', $s->NodeStatus);
        self::assertSame('', $s->getVersion());
        self::assertSame('', $s->Version);
        self::assertNull($s->getLastContact());
        self::assertNull($s->LastContact);
        self::assertTrue($s->getLastTerm()->isZero());
        self::assertTrue($s->LastTerm->isZero());
        self::assertTrue($s->getLastIndex()->isZero());
        self::assertTrue($s->LastIndex->isZero());
        self::assertFalse($s->isHealthy());
        self::assertFalse($s->Healthy);
        self::assertSame('', $s->getRedundancyZone());
        self::assertSame('', $s->RedundancyZone);
        self::assertSame('', $s->getUpgradeVersion());
        self::assertSame('', $s->UpgradeVersion);
        self::assertFalse($s->isReadReplica());
        self::assertFalse($s->ReadReplica);
        self::assertSame(AutopilotServerStatus::UNDEFINED, $s->getStatus());
        self::assertSame(AutopilotServerStatus::UNDEFINED, $s->Status);
        self::assertSame(AutopilotServerType::UNDEFINED, $s->getNodeType());
        self::assertSame(AutopilotServerType::UNDEFINED, $s->NodeType);
    }

    public function testConstructorWithValues(): void
    {
        $s = new AutopilotServer(
            ID: 'srv-1',
            Name: 'node-1',
            Address: '10.0.0.1:8300',
            NodeStatus: 'alive',
            Version: '1.22.0',
            LastContact: '15ms',
            LastTerm: 3,
            LastIndex: 100,
            Healthy: true,
            RedundancyZone: 'zone-a',
            UpgradeVersion: '1.22.0',
            ReadReplica: false,
            Status: AutopilotServerStatus::Leader,
            NodeType: AutopilotServerType::Voter,
        );
        self::assertSame('srv-1', $s->getID());
        self::assertSame('srv-1', $s->ID);
        self::assertSame('3', (string)$s->getLastTerm());
        self::assertSame('100', (string)$s->LastIndex);
        self::assertTrue($s->isHealthy());
        self::assertTrue($s->Healthy);
        self::assertSame(AutopilotServerStatus::Leader, $s->getStatus());
        self::assertSame(AutopilotServerStatus::Leader, $s->Status);
        self::assertSame(AutopilotServerType::Voter, $s->getNodeType());
        self::assertSame(AutopilotServerType::Voter, $s->NodeType);
    }

    public function testConstructorWithEnumStringValues(): void
    {
        $s = new AutopilotServer(
            Status: 'leader',
            NodeType: 'voter',
        );
        self::assertSame(AutopilotServerStatus::Leader, $s->getStatus());
        self::assertSame(AutopilotServerType::Voter, $s->getNodeType());
    }

    public function testFluentSetters(): void
    {
        $s = new AutopilotServer();
        $result = $s
            ->setID('i')
            ->setName('n')
            ->setAddress('a')
            ->setNodeStatus('ns')
            ->setVersion('v')
            ->setLastContact('10ms')
            ->setLastTerm(1)
            ->setLastIndex(50)
            ->setHealthy(true)
            ->setRedundancyZone('z')
            ->setUpgradeVersion('uv')
            ->setReadReplica(true)
            ->setStatus(AutopilotServerStatus::Voter)
            ->setNodeType(AutopilotServerType::ReadReplica);
        self::assertSame($s, $result);
        self::assertSame('i', $s->ID);
        self::assertSame('n', $s->Name);
        self::assertSame('a', $s->Address);
        self::assertSame('ns', $s->NodeStatus);
        self::assertSame('v', $s->Version);
        self::assertSame('1', (string)$s->LastTerm);
        self::assertSame('50', (string)$s->LastIndex);
        self::assertTrue($s->Healthy);
        self::assertSame('z', $s->RedundancyZone);
        self::assertSame('uv', $s->UpgradeVersion);
        self::assertTrue($s->ReadReplica);
        self::assertSame(AutopilotServerStatus::Voter, $s->Status);
        self::assertSame(AutopilotServerType::ReadReplica, $s->NodeType);
    }

    public function testSetStatusFromString(): void
    {
        $s = new AutopilotServer();
        $s->setStatus('leader');
        self::assertSame(AutopilotServerStatus::Leader, $s->getStatus());
    }

    public function testSetNodeTypeFromString(): void
    {
        $s = new AutopilotServer();
        $s->setNodeType('voter');
        self::assertSame(AutopilotServerType::Voter, $s->getNodeType());
    }

    public function testJsonSerialize(): void
    {
        $s = new AutopilotServer(ID: 'x', Name: 'y', Healthy: true);
        $out = $s->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertTrue($out->Healthy);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'as1';
        $decoded->Name = 'node';
        $decoded->Address = '10.0.0.1:8300';
        $decoded->NodeStatus = 'alive';
        $decoded->Version = '1.22.0';
        $decoded->LastTerm = 3;
        $decoded->LastIndex = 100;
        $decoded->Healthy = true;
        $decoded->StableSince = '2024-01-01T00:00:00Z';
        $decoded->ReadReplica = false;
        $decoded->Status = 'voter';
        $decoded->NodeType = 'voter';

        $s = AutopilotServer::jsonUnserialize($decoded);
        self::assertSame('as1', $s->getID());
        self::assertTrue($s->isHealthy());
        self::assertSame(AutopilotServerStatus::Voter, $s->getStatus());
        self::assertSame(AutopilotServerType::Voter, $s->getNodeType());
        self::assertSame('3', (string)$s->LastTerm);
        self::assertSame('100', (string)$s->LastIndex);
    }

    public function testLastContactRoundTrip(): void
    {
        $s = AutopilotServer::jsonUnserialize(decoded: json_decode('{"ID":"x","LastContact":"10ms"}', false));
        self::assertSame(10000000, $s->getLastContact()->Nanoseconds());
        $out = $s->jsonSerialize();
        self::assertSame('10ms', $out->LastContact);
        self::assertObjectNotHasProperty('lastContact', $out);
    }

    public function testUint64FieldsAcceptIntStringAndBigInteger(): void
    {
        $s = new AutopilotServer(LastTerm: BigInteger::of(4), LastIndex: '18446744073709551615');
        self::assertSame('4', (string)$s->getLastTerm());
        self::assertSame('18446744073709551615', (string)$s->LastIndex);

        $s->setLastTerm('9223372036854775808')->setLastIndex(BigInteger::of(6));
        self::assertSame('9223372036854775808', (string)$s->LastTerm);
        self::assertTrue($s->getLastIndex()->isEqualTo(6));

        $s->setLastTerm(1);
        self::assertSame('1', (string)$s->LastTerm);

        $out = $s->jsonSerialize();
        self::assertSame($s->LastTerm, $out->LastTerm);
        self::assertSame($s->LastIndex, $out->LastIndex);
    }

    public function testJsonUnserializeLargeValues(): void
    {
        $s = AutopilotServer::jsonUnserialize(json_decode('{"ID":"a","LastTerm":"9223372036854775808","LastIndex":18446744073709551615}', flags: JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR));
        self::assertSame('9223372036854775808', (string)$s->LastTerm);
        self::assertSame('18446744073709551615', (string)$s->LastIndex);
    }
}

