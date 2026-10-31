<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use Brick\Math\BigInteger;
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
        self::assertSame('0', (string)$s->getReplicatedIndex());
        self::assertSame('0', (string)$s->getReplicatedRoleIndex());
        self::assertSame('0', (string)$s->getReplicatedTokenIndex());
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
        self::assertSame('10', (string)$s->getReplicatedIndex());
        self::assertSame('5', (string)$s->getReplicatedRoleIndex());
        self::assertSame('7', (string)$s->getReplicatedTokenIndex());
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
        self::assertSame('10', (string)$s->getReplicatedIndex());
    }

    public function testUint64Fields(): void
    {
        $max = '18446744073709551615';
        $s = new ACLReplicationStatus(ReplicatedIndex: 1, ReplicatedRoleIndex: '2', ReplicatedTokenIndex: BigInteger::of($max));
        self::assertSame('1', (string)$s->ReplicatedIndex);
        self::assertSame('2', (string)$s->getReplicatedRoleIndex());
        self::assertSame($max, (string)$s->getReplicatedTokenIndex());

        $s->setReplicatedIndex(BigInteger::of(3))->setReplicatedRoleIndex(4)->setReplicatedTokenIndex('5');
        self::assertSame('3', (string)$s->getReplicatedIndex());
        self::assertSame('4', (string)$s->ReplicatedRoleIndex);
        self::assertSame('5', (string)$s->ReplicatedTokenIndex);

        $d = new \stdClass();
        $d->ReplicatedIndex = $max;
        $d->ReplicatedRoleIndex = $max;
        $d->ReplicatedTokenIndex = 6;
        $s = ACLReplicationStatus::jsonUnserialize($d);
        self::assertSame($max, (string)$s->getReplicatedIndex());
        self::assertSame($max, (string)$s->getReplicatedRoleIndex());
        self::assertSame('6', (string)$s->getReplicatedTokenIndex());
        self::assertSame($max, (string)$s->jsonSerialize()->ReplicatedIndex);
    }
}

