<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

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
        self::assertSame('', $s->getName());
        self::assertSame('', $s->getAddress());
        self::assertSame('', $s->getNodeStatus());
        self::assertSame('', $s->getVersion());
        self::assertNull($s->getLastContact());
        self::assertSame(0, $s->getLastTerm());
        self::assertSame(0, $s->getLastIndex());
        self::assertFalse($s->isHealthy());
        self::assertSame('', $s->getRedundancyZone());
        self::assertSame('', $s->getUpgradeVersion());
        self::assertFalse($s->isReadReplica());
        self::assertSame(AutopilotServerStatus::UNDEFINED, $s->getStatus());
        self::assertSame(AutopilotServerType::UNDEFINED, $s->getNodeType());
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
            readReplica: false,
            status: AutopilotServerStatus::Leader,
            NodeType: AutopilotServerType::Voter,
        );
        self::assertSame('srv-1', $s->getID());
        self::assertTrue($s->isHealthy());
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
        self::assertSame(AutopilotServerStatus::Voter, $s->getStatus());
        self::assertSame(AutopilotServerType::ReadReplica, $s->getNodeType());
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
    }
}

