<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\TxnOp;
use DCarbone\PHPConsulAPI\Txn\TxnOps;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TxnOpsTest extends TestCase
{
    public function testConstructorEmpty(): void
    {
        $ops = new TxnOps();
        self::assertCount(0, $ops);
    }

    public function testConstructorWithOps(): void
    {
        $ops = new TxnOps([new TxnOp(), new TxnOp()]);
        self::assertCount(2, $ops);
    }

    public function testCountable(): void
    {
        $ops = new TxnOps([new TxnOp()]);
        self::assertSame(1, $ops->count());
    }

    public function testArrayAccessExists(): void
    {
        $ops = new TxnOps([new TxnOp()]);
        self::assertTrue(isset($ops[0]));
        self::assertFalse(isset($ops[1]));
    }

    public function testArrayAccessGet(): void
    {
        $op = new TxnOp();
        $ops = new TxnOps([$op]);
        self::assertSame($op, $ops[0]);
    }

    public function testArrayAccessAppend(): void
    {
        $ops = new TxnOps();
        $ops[] = new TxnOp();
        self::assertCount(1, $ops);
    }

    public function testArrayAccessSet(): void
    {
        $ops = new TxnOps([new TxnOp()]);
        $newOp = new TxnOp();
        $ops[0] = $newOp;
        self::assertSame($newOp, $ops[0]);
    }

    public function testArrayAccessUnset(): void
    {
        $ops = new TxnOps([new TxnOp()]);
        unset($ops[0]);
        self::assertFalse(isset($ops[0]));
    }

    public function testIteratorAggregate(): void
    {
        $op1 = new TxnOp();
        $op2 = new TxnOp();
        $ops = new TxnOps([$op1, $op2]);
        $items = [];
        foreach ($ops as $op) {
            $items[] = $op;
        }
        self::assertCount(2, $items);
        self::assertSame($op1, $items[0]);
    }

    public function testAll(): void
    {
        $op = new TxnOp();
        $ops = new TxnOps([$op]);
        self::assertSame([$op], $ops->all());
    }

    public function testJsonSerialize(): void
    {
        $ops = new TxnOps([new TxnOp()]);
        $out = $ops->jsonSerialize();
        self::assertIsArray($out);
        self::assertCount(1, $out);
    }
}

