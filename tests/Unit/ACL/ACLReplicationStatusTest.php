<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLReplicationStatus;
use DCarbone\Go\Time;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLReplicationStatusTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new ACLReplicationStatus();
        self::assertFalse($s->isEnabled());
        self::assertFalse($s->isRunning());
        self::assertSame('', $s->getSourceDatacenter());
        self::assertSame(0, $s->getReplicatedIndex());
        self::assertSame(0, $s->getReplicatedRoleIndex());
        self::assertSame(0, $s->getReplicatedTokenIndex());
        self::assertInstanceOf(Time\Time::class, $s->getLastSuccess());
        self::assertInstanceOf(Time\Time::class, $s->getLastError());
    }

    public function testConstructorWithParams(): void
    {
        $s = new ACLReplicationStatus(
            Enabled: true,
            Running: true,
            SourceDatacenter: 'dc1',
            ReplicatedIndex: 10,
            ReplicatedRoleIndex: 5,
            ReplicatedTokenIndex: 7,
        );
        self::assertTrue($s->isEnabled());
        self::assertTrue($s->isRunning());
        self::assertSame('dc1', $s->getSourceDatacenter());
        self::assertSame(10, $s->getReplicatedIndex());
        self::assertSame(5, $s->getReplicatedRoleIndex());
        self::assertSame(7, $s->getReplicatedTokenIndex());
    }

    public function testFluentSetters(): void
    {
        $s = new ACLReplicationStatus();
        $result = $s->setEnabled(true)->setRunning(true)->setSourceDatacenter('dc2')
            ->setReplicatedIndex(1)->setReplicatedRoleIndex(2)->setReplicatedTokenIndex(3);
        self::assertSame($s, $result);
        self::assertTrue($s->isEnabled());
    }

    public function testJsonSerialize(): void
    {
        $s = new ACLReplicationStatus(Enabled: true, Running: true, SourceDatacenter: 'dc1');
        $out = $s->jsonSerialize();
        self::assertTrue($out->Enabled);
        self::assertTrue($out->Running);
        self::assertSame('dc1', $out->SourceDatacenter);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Enabled = true;
        $d->Running = false;
        $d->SourceDatacenter = 'dc1';
        $d->ReplicatedIndex = 10;
        $d->ReplicatedRoleIndex = 5;
        $d->ReplicatedTokenIndex = 7;
        $d->LastSuccess = '2025-01-01T00:00:00Z';
        $d->LastError = '2025-01-01T00:00:00Z';
        $s = ACLReplicationStatus::jsonUnserialize($d);
        self::assertTrue($s->isEnabled());
        self::assertFalse($s->isRunning());
        self::assertSame(10, $s->getReplicatedIndex());
    }
}

