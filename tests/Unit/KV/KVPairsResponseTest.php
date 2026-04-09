<?php

namespace DCarbone\PHPConsulAPITests\Unit\KV;

use DCarbone\PHPConsulAPI\KV\KVPairs;
use DCarbone\PHPConsulAPI\KV\KVPairsResponse;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPI\QueryMeta;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KVPairsResponseTest extends TestCase
{
    public function testDefaultState(): void
    {
        $resp = new KVPairsResponse();
        self::assertInstanceOf(KVPairs::class, $resp->KVPairs);
        self::assertCount(0, $resp->KVPairs);
        self::assertNull($resp->Err);
    }

    public function testGetValueReturnsKVPairs(): void
    {
        $resp = new KVPairsResponse();
        self::assertInstanceOf(KVPairs::class, $resp->getValue());
        self::assertSame($resp->KVPairs, $resp->getValue());
    }

    public function testUnmarshalValueWithNull(): void
    {
        // First populate, then unmarshal null to verify it resets
        $obj = new \stdClass();
        $obj->Key = 'k';
        $obj->Value = base64_encode('v');
        $obj->CreateIndex = 0;
        $obj->ModifyIndex = 0;
        $obj->LockIndex = 0;
        $obj->Flags = 0;
        $obj->Session = '';

        $resp = new KVPairsResponse();
        $resp->unmarshalValue([$obj]);
        self::assertCount(1, $resp->KVPairs);

        $resp->unmarshalValue(null);
        self::assertCount(0, $resp->KVPairs);
    }

    public function testUnmarshalValueWithValidData(): void
    {
        $obj1 = new \stdClass();
        $obj1->Key = 'a/1';
        $obj1->Value = base64_encode('val1');
        $obj1->CreateIndex = 1;
        $obj1->ModifyIndex = 2;
        $obj1->LockIndex = 0;
        $obj1->Flags = 0;
        $obj1->Session = '';

        $obj2 = new \stdClass();
        $obj2->Key = 'a/2';
        $obj2->Value = base64_encode('val2');
        $obj2->CreateIndex = 3;
        $obj2->ModifyIndex = 4;
        $obj2->LockIndex = 0;
        $obj2->Flags = 0;
        $obj2->Session = '';

        $resp = new KVPairsResponse();
        $resp->unmarshalValue([$obj1, $obj2]);

        self::assertCount(2, $resp->KVPairs);
        self::assertSame('a/1', $resp->KVPairs[0]->getKey());
        self::assertSame('val1', $resp->KVPairs[0]->getValue());
        self::assertSame('a/2', $resp->KVPairs[1]->getKey());
        self::assertSame('val2', $resp->KVPairs[1]->getValue());
    }

    public function testArrayAccessOffset0ReturnsValue(): void
    {
        $resp = new KVPairsResponse();
        self::assertInstanceOf(KVPairs::class, $resp[0]);
    }

    public function testArrayAccessOffset1ReturnsQueryMeta(): void
    {
        $resp = new KVPairsResponse();
        $qm = new QueryMeta(RequestUrl: 'http://localhost', RequestTime: 0);
        $resp->setQueryMeta($qm);
        self::assertSame($qm, $resp[1]);
    }

    public function testArrayAccessOffset2ReturnsErr(): void
    {
        $resp = new KVPairsResponse();
        self::assertNull($resp[2]);

        $err = new Error('test error');
        $resp->Err = $err;
        self::assertSame($err, $resp[2]);
    }

    public function testArrayAccessOffsetExists(): void
    {
        $resp = new KVPairsResponse();
        self::assertTrue(isset($resp[0]));
        self::assertTrue(isset($resp[1]));
        self::assertTrue(isset($resp[2]));
        self::assertFalse(isset($resp[3]));
        self::assertFalse(isset($resp[-1]));
    }

    public function testArrayAccessOffsetGetThrowsOutOfRange(): void
    {
        $this->expectException(\OutOfRangeException::class);
        $resp = new KVPairsResponse();
        $resp[3];
    }

    public function testListDestructuring(): void
    {
        $obj = new \stdClass();
        $obj->Key = 'test';
        $obj->Value = base64_encode('value');
        $obj->CreateIndex = 1;
        $obj->ModifyIndex = 2;
        $obj->LockIndex = 0;
        $obj->Flags = 0;
        $obj->Session = '';

        $resp = new KVPairsResponse();
        $resp->unmarshalValue([$obj]);
        $qm = new QueryMeta(RequestUrl: 'http://localhost', RequestTime: 0);
        $resp->setQueryMeta($qm);

        [$pairs, $queryMeta, $err] = $resp;
        self::assertInstanceOf(KVPairs::class, $pairs);
        self::assertCount(1, $pairs);
        self::assertSame($qm, $queryMeta);
        self::assertNull($err);
    }

    public function testListDestructuringWithError(): void
    {
        $resp = new KVPairsResponse();
        $resp->Err = new Error('fail');

        [$pairs, $queryMeta, $err] = $resp;
        self::assertInstanceOf(KVPairs::class, $pairs);
        self::assertCount(0, $pairs);
        self::assertNull($queryMeta);
        self::assertInstanceOf(Error::class, $err);
    }

    public function testUnmarshalValueIsIterable(): void
    {
        $items = [];
        for ($i = 0; $i < 5; $i++) {
            $obj = new \stdClass();
            $obj->Key = "prefix/key{$i}";
            $obj->Value = base64_encode("value{$i}");
            $obj->CreateIndex = $i;
            $obj->ModifyIndex = $i;
            $obj->LockIndex = 0;
            $obj->Flags = 0;
            $obj->Session = '';
            $items[] = $obj;
        }

        $resp = new KVPairsResponse();
        $resp->unmarshalValue($items);

        $keys = [];
        foreach ($resp->KVPairs as $pair) {
            $keys[] = $pair->getKey();
        }
        self::assertSame(['prefix/key0', 'prefix/key1', 'prefix/key2', 'prefix/key3', 'prefix/key4'], $keys);
    }

    public function testUnmarshalEmptyArray(): void
    {
        $resp = new KVPairsResponse();
        $resp->unmarshalValue([]);
        self::assertCount(0, $resp->KVPairs);
    }
}

