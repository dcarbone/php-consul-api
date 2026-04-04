<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\TxnResult;
use DCarbone\PHPConsulAPI\Txn\TxnResults;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TxnResultsTest extends TestCase
{
    public function testConstructorEmpty(): void
    {
        $r = new TxnResults();
        self::assertCount(0, $r);
    }

    public function testConstructorWithResults(): void
    {
        $r = new TxnResults([new TxnResult(), new TxnResult()]);
        self::assertCount(2, $r);
    }

    public function testCountable(): void
    {
        $r = new TxnResults([new TxnResult()]);
        self::assertSame(1, $r->count());
    }

    public function testArrayAccess(): void
    {
        $result = new TxnResult();
        $r = new TxnResults([$result]);
        self::assertTrue(isset($r[0]));
        self::assertSame($result, $r[0]);
        $r[] = new TxnResult();
        self::assertCount(2, $r);
        unset($r[0]);
        self::assertFalse(isset($r[0]));
    }

    public function testIteratorAggregate(): void
    {
        $r = new TxnResults([new TxnResult(), new TxnResult()]);
        $items = [];
        foreach ($r as $result) {
            $items[] = $result;
        }
        self::assertCount(2, $items);
    }

    public function testAll(): void
    {
        $result = new TxnResult();
        $r = new TxnResults([$result]);
        self::assertSame([$result], $r->all());
    }

    public function testJsonSerialize(): void
    {
        $r = new TxnResults([new TxnResult()]);
        $out = $r->jsonSerialize();
        self::assertIsArray($out);
        self::assertCount(1, $out);
    }
}

