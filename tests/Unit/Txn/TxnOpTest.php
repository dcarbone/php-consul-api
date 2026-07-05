<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\KVTxnOp;
use DCarbone\PHPConsulAPI\Txn\TxnOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TxnOpTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $op = new TxnOp();
        self::assertNull($op->getKV());
        self::assertNull($op->getNode());
        self::assertNull($op->getService());
        self::assertNull($op->getCheck());
    }

    public function testConstructorWithKV(): void
    {
        $kvOp = new KVTxnOp(Key: 'test');
        $op = new TxnOp(KV: $kvOp);
        self::assertNotNull($op->getKV());
        self::assertSame('test', $op->getKV()->getKey());
    }

    public function testFluentSetters(): void
    {
        $op = new TxnOp();
        $result = $op->setKV(null)->setNode(null)->setService(null)->setCheck(null);
        self::assertSame($op, $result);
    }

    public function testJsonSerializeOmitsNulls(): void
    {
        $op = new TxnOp();
        $out = $op->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertObjectNotHasProperty('KV', $out);
        self::assertObjectNotHasProperty('Node', $out);
    }

    public function testJsonSerializeIncludesNonNull(): void
    {
        $op = new TxnOp(KV: new KVTxnOp(Key: 'k'));
        $out = $op->jsonSerialize();
        self::assertObjectHasProperty('KV', $out);
    }
}

