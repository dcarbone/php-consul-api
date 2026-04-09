<?php

namespace DCarbone\PHPConsulAPITests\Unit\Session;

use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\Session\SessionEntriesQueryResponse;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SessionEntriesQueryResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $resp = new SessionEntriesQueryResponse();
        self::assertSame([], $resp->SessionEntries);
        self::assertSame([], $resp->getValue());
        self::assertNull($resp->Err);
    }

    public function testGetValueReturnsSessionEntries(): void
    {
        $resp = new SessionEntriesQueryResponse();
        self::assertSame($resp->SessionEntries, $resp->getValue());
    }

    public function testUnmarshalValue(): void
    {
        $d1 = new \stdClass();
        $d1->CreateIndex = 1;
        $d1->ID = 'sess-1';
        $d1->Name = 'session-one';
        $d1->Node = 'node-1';
        $d1->LockDelay = 15000000000;
        $d1->Behavior = 'release';
        $d1->TTL = '30s';
        $d1->Checks = ['serfHealth'];
        $d1->NodeChecks = [];
        $d1->ServiceChecks = [];

        $d2 = new \stdClass();
        $d2->CreateIndex = 2;
        $d2->ID = 'sess-2';
        $d2->Name = 'session-two';
        $d2->Node = 'node-2';
        $d2->LockDelay = 0;
        $d2->Behavior = 'delete';
        $d2->TTL = '60s';
        $d2->Checks = [];
        $d2->NodeChecks = ['nc1'];
        $d2->ServiceChecks = [];

        $resp = new SessionEntriesQueryResponse();
        $resp->unmarshalValue([$d1, $d2]);

        self::assertCount(2, $resp->SessionEntries);
        self::assertCount(2, $resp->getValue());
        self::assertInstanceOf(SessionEntry::class, $resp->SessionEntries[0]);
        self::assertInstanceOf(SessionEntry::class, $resp->SessionEntries[1]);
        self::assertSame('sess-1', $resp->SessionEntries[0]->getID());
        self::assertSame('session-one', $resp->SessionEntries[0]->getName());
        self::assertSame('sess-2', $resp->SessionEntries[1]->getID());
        self::assertSame('session-two', $resp->SessionEntries[1]->getName());
    }

    public function testUnmarshalValueResetsEntries(): void
    {
        $d = new \stdClass();
        $d->CreateIndex = 1;
        $d->ID = 'sess-1';
        $d->Name = 'session-one';
        $d->Node = 'node-1';
        $d->LockDelay = 0;
        $d->Behavior = '';
        $d->TTL = '';
        $d->Checks = [];
        $d->NodeChecks = [];
        $d->ServiceChecks = [];

        $resp = new SessionEntriesQueryResponse();
        $resp->unmarshalValue([$d]);
        self::assertCount(1, $resp->SessionEntries);

        $resp->unmarshalValue([]);
        self::assertSame([], $resp->SessionEntries);
    }

    public function testArrayAccessOffset0ReturnsValue(): void
    {
        $resp = new SessionEntriesQueryResponse();
        self::assertSame([], $resp[0]);
    }

    public function testArrayAccessOffset1ReturnsQueryMeta(): void
    {
        $resp = new SessionEntriesQueryResponse();
        $qm = new QueryMeta(RequestUrl: 'http://localhost', RequestTime: 0);
        $resp->setQueryMeta($qm);
        self::assertSame($qm, $resp[1]);
    }

    public function testArrayAccessOffset2ReturnsErr(): void
    {
        $resp = new SessionEntriesQueryResponse();
        self::assertNull($resp[2]);

        $err = new Error('test error');
        $resp->Err = $err;
        self::assertSame($err, $resp[2]);
    }

    public function testArrayAccessOffsetExists(): void
    {
        $resp = new SessionEntriesQueryResponse();
        self::assertTrue(isset($resp[0]));
        self::assertTrue(isset($resp[1]));
        self::assertTrue(isset($resp[2]));
        self::assertFalse(isset($resp[3]));
        self::assertFalse(isset($resp[-1]));
    }

    public function testArrayAccessOffsetGetThrowsOutOfRange(): void
    {
        $this->expectException(\OutOfRangeException::class);
        $resp = new SessionEntriesQueryResponse();
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

        $resp = new SessionEntriesQueryResponse();
        $resp->unmarshalValue([$d]);
        $qm = new QueryMeta(RequestUrl: 'http://localhost', RequestTime: 0);
        $resp->setQueryMeta($qm);

        [$entries, $queryMeta, $err] = $resp;
        self::assertIsArray($entries);
        self::assertCount(1, $entries);
        self::assertSame($qm, $queryMeta);
        self::assertNull($err);
    }

    public function testListDestructuringWithError(): void
    {
        $resp = new SessionEntriesQueryResponse();
        $resp->Err = new Error('fail');

        [$entries, $queryMeta, $err] = $resp;
        self::assertSame([], $entries);
        self::assertNull($queryMeta);
        self::assertInstanceOf(Error::class, $err);
    }
}

