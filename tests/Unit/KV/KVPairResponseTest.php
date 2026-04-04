<?php

namespace DCarbone\PHPConsulAPITests\Unit\KV;

use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\KV\KVPairResponse;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPI\QueryMeta;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KVPairResponseTest extends TestCase
{
    public function testDefaultState(): void
    {
        $resp = new KVPairResponse();
        self::assertNull($resp->KVPair);
        self::assertNull($resp->getValue());
        self::assertNull($resp->Err);
    }

    public function testUnmarshalValueWithNull(): void
    {
        $resp = new KVPairResponse();
        $resp->unmarshalValue(null);
        self::assertNull($resp->KVPair);
        self::assertNull($resp->getValue());
    }

    public function testUnmarshalValueWithValidData(): void
    {
        $decoded = new \stdClass();
        $decoded->Key = 'foo/bar';
        $decoded->Value = base64_encode('hello world');
        $decoded->CreateIndex = 10;
        $decoded->ModifyIndex = 20;
        $decoded->LockIndex = 0;
        $decoded->Flags = 0;
        $decoded->Session = '';

        $resp = new KVPairResponse();
        $resp->unmarshalValue($decoded);

        self::assertInstanceOf(KVPair::class, $resp->KVPair);
        self::assertSame('foo/bar', $resp->KVPair->getKey());
        self::assertSame('hello world', $resp->KVPair->getValue());
        self::assertSame(10, $resp->KVPair->getCreateIndex());
        self::assertSame(20, $resp->KVPair->getModifyIndex());
    }

    public function testGetValueReturnsSameAsKVPairField(): void
    {
        $decoded = new \stdClass();
        $decoded->Key = 'test';
        $decoded->Value = base64_encode('val');
        $decoded->CreateIndex = 0;
        $decoded->ModifyIndex = 0;
        $decoded->LockIndex = 0;
        $decoded->Flags = 0;
        $decoded->Session = '';

        $resp = new KVPairResponse();
        $resp->unmarshalValue($decoded);

        self::assertSame($resp->KVPair, $resp->getValue());
    }

    public function testArrayAccessOffset0ReturnsValue(): void
    {
        $decoded = new \stdClass();
        $decoded->Key = 'k';
        $decoded->Value = base64_encode('v');
        $decoded->CreateIndex = 0;
        $decoded->ModifyIndex = 0;
        $decoded->LockIndex = 0;
        $decoded->Flags = 0;
        $decoded->Session = '';

        $resp = new KVPairResponse();
        $resp->unmarshalValue($decoded);

        self::assertSame($resp->KVPair, $resp[0]);
    }

    public function testArrayAccessOffset1ReturnsQueryMeta(): void
    {
        $resp = new KVPairResponse();
        $qm = new QueryMeta(RequestUrl: 'http://localhost', RequestTime: 0);
        $resp->setQueryMeta($qm);
        self::assertSame($qm, $resp[1]);
    }

    public function testArrayAccessOffset2ReturnsErr(): void
    {
        $resp = new KVPairResponse();
        self::assertNull($resp[2]);

        $err = new Error('test error');
        $resp->Err = $err;
        self::assertSame($err, $resp[2]);
    }

    public function testArrayAccessOffsetExists(): void
    {
        $resp = new KVPairResponse();
        self::assertTrue(isset($resp[0]));
        self::assertTrue(isset($resp[1]));
        self::assertTrue(isset($resp[2]));
        self::assertFalse(isset($resp[3]));
        self::assertFalse(isset($resp[-1]));
    }

    public function testArrayAccessOffsetGetThrowsOutOfRange(): void
    {
        $this->expectException(\OutOfRangeException::class);
        $resp = new KVPairResponse();
        $resp[3];
    }

    public function testArrayAccessOffsetSetThrows(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $resp = new KVPairResponse();
        $resp[0] = null;
    }

    public function testArrayAccessOffsetUnsetThrows(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $resp = new KVPairResponse();
        unset($resp[0]);
    }

    public function testListDestructuring(): void
    {
        $decoded = new \stdClass();
        $decoded->Key = 'test';
        $decoded->Value = base64_encode('value');
        $decoded->CreateIndex = 5;
        $decoded->ModifyIndex = 10;
        $decoded->LockIndex = 0;
        $decoded->Flags = 0;
        $decoded->Session = '';

        $resp = new KVPairResponse();
        $resp->unmarshalValue($decoded);
        $qm = new QueryMeta(RequestUrl: 'http://localhost', RequestTime: 0);
        $resp->setQueryMeta($qm);

        [$kv, $queryMeta, $err] = $resp;
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame('test', $kv->getKey());
        self::assertSame($qm, $queryMeta);
        self::assertNull($err);
    }

    public function testListDestructuringWithError(): void
    {
        $resp = new KVPairResponse();
        $resp->Err = new Error('something went wrong');

        [$kv, $queryMeta, $err] = $resp;
        self::assertNull($kv);
        self::assertNull($queryMeta);
        self::assertInstanceOf(Error::class, $err);
        self::assertSame('something went wrong', (string)$err);
    }

    public function testUnmarshalOverwritesPreviousValue(): void
    {
        $decoded1 = new \stdClass();
        $decoded1->Key = 'first';
        $decoded1->Value = base64_encode('v1');
        $decoded1->CreateIndex = 0;
        $decoded1->ModifyIndex = 0;
        $decoded1->LockIndex = 0;
        $decoded1->Flags = 0;
        $decoded1->Session = '';

        $decoded2 = new \stdClass();
        $decoded2->Key = 'second';
        $decoded2->Value = base64_encode('v2');
        $decoded2->CreateIndex = 0;
        $decoded2->ModifyIndex = 0;
        $decoded2->LockIndex = 0;
        $decoded2->Flags = 0;
        $decoded2->Session = '';

        $resp = new KVPairResponse();
        $resp->unmarshalValue($decoded1);
        self::assertSame('first', $resp->KVPair->getKey());

        $resp->unmarshalValue($decoded2);
        self::assertSame('second', $resp->KVPair->getKey());
    }

    public function testUnmarshalNullAfterValueClearsIt(): void
    {
        $decoded = new \stdClass();
        $decoded->Key = 'k';
        $decoded->Value = base64_encode('v');
        $decoded->CreateIndex = 0;
        $decoded->ModifyIndex = 0;
        $decoded->LockIndex = 0;
        $decoded->Flags = 0;
        $decoded->Session = '';

        $resp = new KVPairResponse();
        $resp->unmarshalValue($decoded);
        self::assertNotNull($resp->KVPair);

        $resp->unmarshalValue(null);
        self::assertNull($resp->KVPair);
    }
}

