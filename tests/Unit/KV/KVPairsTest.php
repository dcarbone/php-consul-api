<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\KV;

use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\KV\KVPairs;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KVPairsTest extends TestCase
{
    public function testConstructorEmpty(): void
    {
        $pairs = new KVPairs();
        self::assertCount(0, $pairs);
    }

    public function testConstructorWithPairs(): void
    {
        $kv1 = new KVPair(Key: 'a');
        $kv2 = new KVPair(Key: 'b');
        $pairs = new KVPairs($kv1, $kv2);
        self::assertCount(2, $pairs);
    }

    public function testCountable(): void
    {
        $pairs = new KVPairs(new KVPair(Key: 'x'), new KVPair(Key: 'y'), new KVPair(Key: 'z'));
        self::assertSame(3, $pairs->count());
    }

    public function testIteratorAggregate(): void
    {
        $kv1 = new KVPair(Key: 'first');
        $kv2 = new KVPair(Key: 'second');
        $pairs = new KVPairs($kv1, $kv2);

        $keys = [];
        foreach ($pairs as $pair) {
            $keys[] = $pair->getKey();
        }
        self::assertSame(['first', 'second'], $keys);
    }

    public function testArrayAccessOffsetExists(): void
    {
        $pairs = new KVPairs(new KVPair(Key: 'a'));
        self::assertTrue(isset($pairs[0]));
        self::assertFalse(isset($pairs[1]));
    }

    public function testArrayAccessOffsetGet(): void
    {
        $kv = new KVPair(Key: 'test');
        $pairs = new KVPairs($kv);
        self::assertSame('test', $pairs[0]->getKey());
    }

    public function testArrayAccessOffsetGetThrowsOnInvalidOffset(): void
    {
        $this->expectException(\OutOfRangeException::class);
        $pairs = new KVPairs();
        $pairs[0];
    }

    public function testArrayAccessOffsetSetAppend(): void
    {
        $pairs = new KVPairs();
        $pairs[] = new KVPair(Key: 'appended');
        self::assertCount(1, $pairs);
        self::assertSame('appended', $pairs[0]->getKey());
    }

    public function testArrayAccessOffsetSetByIndex(): void
    {
        $pairs = new KVPairs(new KVPair(Key: 'old'));
        $pairs[0] = new KVPair(Key: 'new');
        self::assertSame('new', $pairs[0]->getKey());
    }

    public function testArrayAccessOffsetSetThrowsOnNonKVPair(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $pairs = new KVPairs();
        $pairs[] = 'not a KVPair';
    }

    public function testArrayAccessOffsetSetThrowsOnNonIntOffset(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $pairs = new KVPairs();
        $pairs['key'] = new KVPair(Key: 'x');
    }

    public function testArrayAccessOffsetUnset(): void
    {
        $pairs = new KVPairs(new KVPair(Key: 'a'), new KVPair(Key: 'b'));
        unset($pairs[0]);
        self::assertFalse(isset($pairs[0]));
        self::assertTrue(isset($pairs[1]));
    }

    public function testJsonSerialize(): void
    {
        $kv1 = new KVPair(Key: 'k1', Value: 'v1');
        $kv2 = new KVPair(Key: 'k2', Value: 'v2');
        $pairs = new KVPairs($kv1, $kv2);
        $out = $pairs->jsonSerialize();
        self::assertIsArray($out);
        self::assertCount(2, $out);
        self::assertInstanceOf(KVPair::class, $out[0]);
        self::assertInstanceOf(KVPair::class, $out[1]);
    }

    public function testJsonUnserialize(): void
    {
        $obj1 = new \stdClass();
        $obj1->Key = 'k1';
        $obj1->Value = base64_encode('v1');
        $obj1->CreateIndex = 0;
        $obj1->ModifyIndex = 0;
        $obj1->LockIndex = 0;
        $obj1->Flags = 0;
        $obj1->Session = '';

        $obj2 = new \stdClass();
        $obj2->Key = 'k2';
        $obj2->Value = base64_encode('v2');
        $obj2->CreateIndex = 0;
        $obj2->ModifyIndex = 0;
        $obj2->LockIndex = 0;
        $obj2->Flags = 0;
        $obj2->Session = '';

        $pairs = KVPairs::jsonUnserialize([$obj1, $obj2]);
        self::assertCount(2, $pairs);
        self::assertSame('k1', $pairs[0]->getKey());
        self::assertSame('v1', $pairs[0]->getValue());
        self::assertSame('k2', $pairs[1]->getKey());
        self::assertSame('v2', $pairs[1]->getValue());
    }
}

