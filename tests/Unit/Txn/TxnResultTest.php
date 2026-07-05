<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\Txn\TxnResult;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TxnResultTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new TxnResult();
        self::assertNull($r->getKV());
        self::assertNull($r->getNode());
        self::assertNull($r->getService());
        self::assertNull($r->getCheck());
    }

    public function testConstructorWithValues(): void
    {
        $kv = new KVPair(Key: 'test');
        $r = new TxnResult(KV: $kv);
        self::assertNotNull($r->getKV());
        self::assertSame('test', $r->getKV()->getKey());
        self::assertNull($r->getNode());
    }

    public function testFluentSetters(): void
    {
        $r = new TxnResult();
        $kv = new KVPair(Key: 'k');
        $result = $r->setKV($kv)->setNode(null)->setService(null)->setCheck(null);
        self::assertSame($r, $result);
        self::assertNotNull($r->getKV());
    }

    public function testJsonSerialize(): void
    {
        $r = new TxnResult(KV: new KVPair(Key: 'foo'));
        $out = $r->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertObjectHasProperty('KV', $out);
        self::assertObjectNotHasProperty('Node', $out);
    }

    public function testJsonSerializeOmitsNulls(): void
    {
        $r = new TxnResult();
        $out = $r->jsonSerialize();
        self::assertObjectNotHasProperty('KV', $out);
        self::assertObjectNotHasProperty('Node', $out);
        self::assertObjectNotHasProperty('Service', $out);
        self::assertObjectNotHasProperty('Check', $out);
    }
}

