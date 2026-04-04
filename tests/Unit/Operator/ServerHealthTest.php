<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\ServerHealth;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServerHealthTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new ServerHealth();
        self::assertSame('', $s->getID());
        self::assertSame('', $s->getName());
        self::assertSame('', $s->getAddress());
        self::assertSame('', $s->getSerfStatus());
        self::assertSame('', $s->getVersion());
        self::assertFalse($s->isLeader());
        self::assertNull($s->getLastContact());
        self::assertSame(0, $s->getLastTerm());
        self::assertSame(0, $s->getLastIndex());
        self::assertFalse($s->isHealthy());
        self::assertFalse($s->isVoter());
    }

    public function testConstructorWithValues(): void
    {
        $s = new ServerHealth(
            ID: 'srv-1',
            Name: 'node-1',
            Address: '10.0.0.1:8300',
            SerfStatus: 'alive',
            Version: '1.22.0',
            Leader: true,
            LastContact: '15ms',
            LastTerm: 3,
            LastIndex: 100,
            Healthy: true,
            Voter: true,
        );
        self::assertSame('srv-1', $s->getID());
        self::assertSame('node-1', $s->getName());
        self::assertTrue($s->isLeader());
        self::assertNotNull($s->getLastContact());
        self::assertSame(3, $s->getLastTerm());
        self::assertSame(100, $s->getLastIndex());
        self::assertTrue($s->isHealthy());
        self::assertTrue($s->isVoter());
    }

    public function testFluentSetters(): void
    {
        $s = new ServerHealth();
        $result = $s
            ->setID('i')
            ->setName('n')
            ->setAddress('a')
            ->setSerfStatus('alive')
            ->setVersion('v')
            ->setLeader(true)
            ->setLastContact('10ms')
            ->setLastTerm(1)
            ->setLastIndex(50)
            ->setHealthy(true)
            ->setVoter(true);
        self::assertSame($s, $result);
    }

    public function testNullLastContact(): void
    {
        $s = new ServerHealth(LastContact: '5s');
        self::assertNotNull($s->getLastContact());
        $s->setLastContact(null);
        self::assertNull($s->getLastContact());
    }

    public function testJsonSerialize(): void
    {
        $s = new ServerHealth(ID: 'x', Name: 'y', Healthy: true);
        $out = $s->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertTrue($out->Healthy);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'sh1';
        $decoded->Name = 'node';
        $decoded->Address = '10.0.0.1:8300';
        $decoded->SerfStatus = 'alive';
        $decoded->Version = '1.22.0';
        $decoded->Leader = true;
        $decoded->LastContact = '15ms';
        $decoded->LastTerm = 3;
        $decoded->LastIndex = 100;
        $decoded->Healthy = true;
        $decoded->Voter = true;
        $decoded->StableSince = '2024-01-01T00:00:00.000000Z';

        $s = ServerHealth::jsonUnserialize($decoded);
        self::assertSame('sh1', $s->getID());
        self::assertTrue($s->isLeader());
        self::assertTrue($s->isHealthy());
    }
}

