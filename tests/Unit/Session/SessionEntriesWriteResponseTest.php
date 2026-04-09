<?php

namespace DCarbone\PHPConsulAPITests\Unit\Session;

use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPI\Session\SessionEntriesWriteResponse;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SessionEntriesWriteResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $resp = new SessionEntriesWriteResponse();
        self::assertSame([], $resp->SessionEntries);
        self::assertSame([], $resp->getValue());
        self::assertNull($resp->Err);
    }

    public function testGetValueReturnsSessionEntries(): void
    {
        $resp = new SessionEntriesWriteResponse();
        self::assertSame($resp->SessionEntries, $resp->getValue());
    }

    public function testUnmarshalValue(): void
    {
        $d1 = new \stdClass();
        $d1->CreateIndex = 1;
        $d1->ID = 'sess-1';
        $d1->Name = 'renewed-session';
        $d1->Node = 'node-1';
        $d1->LockDelay = 15000000000;
        $d1->Behavior = 'release';
        $d1->TTL = '30s';
        $d1->Checks = ['serfHealth'];
        $d1->NodeChecks = [];
        $d1->ServiceChecks = [];

        $resp = new SessionEntriesWriteResponse();
        $resp->unmarshalValue([$d1]);

        self::assertCount(1, $resp->SessionEntries);
        self::assertCount(1, $resp->getValue());
        self::assertInstanceOf(SessionEntry::class, $resp->SessionEntries[0]);
        self::assertSame('sess-1', $resp->SessionEntries[0]->getID());
        self::assertSame('renewed-session', $resp->SessionEntries[0]->getName());
    }

    public function testUnmarshalValueResetsEntries(): void
    {
        $d = new \stdClass();
        $d->CreateIndex = 1;
        $d->ID = 'sess-1';
        $d->Name = 'test';
        $d->Node = 'n';
        $d->LockDelay = 0;
        $d->Behavior = '';
        $d->TTL = '';
        $d->Checks = [];
        $d->NodeChecks = [];
        $d->ServiceChecks = [];

        $resp = new SessionEntriesWriteResponse();
        $resp->unmarshalValue([$d]);
        self::assertCount(1, $resp->SessionEntries);

        $resp->unmarshalValue([]);
        self::assertSame([], $resp->SessionEntries);
    }

    public function testArrayAccessOffset0ReturnsValue(): void
    {
        $resp = new SessionEntriesWriteResponse();
        self::assertSame([], $resp[0]);
    }

    public function testArrayAccessOffset1ReturnsWriteMeta(): void
    {
        $resp = new SessionEntriesWriteResponse();
        self::assertNull($resp[1]);
    }

    public function testArrayAccessOffset2ReturnsErr(): void
    {
        $resp = new SessionEntriesWriteResponse();
        self::assertNull($resp[2]);

        $err = new Error('test error');
        $resp->Err = $err;
        self::assertSame($err, $resp[2]);
    }

    public function testArrayAccessOffsetExists(): void
    {
        $resp = new SessionEntriesWriteResponse();
        self::assertTrue(isset($resp[0]));
        self::assertTrue(isset($resp[1]));
        self::assertTrue(isset($resp[2]));
        self::assertFalse(isset($resp[3]));
        self::assertFalse(isset($resp[-1]));
    }

    public function testArrayAccessOffsetGetThrowsOutOfRange(): void
    {
        $this->expectException(\OutOfRangeException::class);
        $resp = new SessionEntriesWriteResponse();
        // @phpstan-ignore expr.resultUnused
        $resp[3];
    }

    public function testListDestructuring(): void
    {
        $d = new \stdClass();
        $d->CreateIndex = 1;
        $d->ID = 'sess-1';
        $d->Name = 'test';
        $d->Node = 'n';
        $d->LockDelay = 0;
        $d->Behavior = '';
        $d->TTL = '';
        $d->Checks = [];
        $d->NodeChecks = [];
        $d->ServiceChecks = [];

        $resp = new SessionEntriesWriteResponse();
        $resp->unmarshalValue([$d]);

        [$entries, $writeMeta, $err] = $resp;
        self::assertIsArray($entries);
        self::assertCount(1, $entries);
        self::assertNull($writeMeta);
        self::assertNull($err);
    }

    public function testListDestructuringWithError(): void
    {
        $resp = new SessionEntriesWriteResponse();
        $resp->Err = new Error('fail');

        [$entries, $writeMeta, $err] = $resp;
        self::assertSame([], $entries);
        self::assertNull($writeMeta);
        self::assertInstanceOf(Error::class, $err);
    }
}

