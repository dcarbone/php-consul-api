<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\KVOp;
use DCarbone\PHPConsulAPI\Txn\KVTxnOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KVTxnOpTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $op = new KVTxnOp();
        self::assertSame(KVOp::UNDEFINED, $op->getVerb());
        self::assertSame('', $op->getKey());
        self::assertSame('', $op->getValue());
        self::assertSame(0, $op->getFlags());
        self::assertSame(0, $op->getIndex());
        self::assertSame('', $op->getSession());
        self::assertSame('', $op->getNamespace());
        self::assertSame('', $op->getPartition());
    }

    public function testConstructorWithEnumVerb(): void
    {
        $op = new KVTxnOp(Verb: KVOp::KVSet, Key: 'test/key', Value: 'val');
        self::assertSame(KVOp::KVSet, $op->getVerb());
        self::assertSame('test/key', $op->getKey());
    }

    public function testConstructorWithStringVerb(): void
    {
        $op = new KVTxnOp(Verb: 'get', Key: 'foo');
        self::assertSame(KVOp::KVGet, $op->getVerb());
    }

    public function testSetVerbWithString(): void
    {
        $op = new KVTxnOp();
        $result = $op->setVerb('set');
        self::assertSame($op, $result);
        self::assertSame(KVOp::KVSet, $op->getVerb());
    }

    public function testSetVerbWithEnum(): void
    {
        $op = new KVTxnOp();
        $op->setVerb(KVOp::KVDelete);
        self::assertSame(KVOp::KVDelete, $op->getVerb());
    }

    public function testFluentSetters(): void
    {
        $op = new KVTxnOp();
        $result = $op
            ->setVerb(KVOp::KVSet)
            ->setKey('my/key')
            ->setValue('my-value')
            ->setFlags(5)
            ->setIndex(10)
            ->setSession('sess-1')
            ->setNamespace('ns')
            ->setPartition('pt');

        self::assertSame($op, $result);
        self::assertSame('my/key', $op->getKey());
        self::assertSame('my-value', $op->getValue());
        self::assertSame(5, $op->getFlags());
        self::assertSame(10, $op->getIndex());
        self::assertSame('sess-1', $op->getSession());
        self::assertSame('ns', $op->getNamespace());
        self::assertSame('pt', $op->getPartition());
    }

    public function testJsonSerializeOmitsEmptyNamespaceAndPartition(): void
    {
        $op = new KVTxnOp(Verb: KVOp::KVGet, Key: 'k');
        $out = $op->jsonSerialize();
        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
    }

    public function testJsonSerializeIncludesNonEmptyNamespaceAndPartition(): void
    {
        $op = new KVTxnOp(Verb: KVOp::KVGet, Key: 'k', Namespace: 'ns', Partition: 'pt');
        $out = $op->jsonSerialize();
        self::assertSame('ns', $out->Namespace);
        self::assertSame('pt', $out->Partition);
    }

    public function testJsonUnserializeBase64DecodesValue(): void
    {
        $decoded = new \stdClass();
        $decoded->Verb = 'set';
        $decoded->Key = 'test';
        $decoded->Value = base64_encode('hello');
        $decoded->Flags = 0;
        $decoded->Index = 0;
        $decoded->Session = '';

        $op = KVTxnOp::jsonUnserialize($decoded);
        self::assertSame(KVOp::KVSet, $op->getVerb());
        self::assertSame('hello', $op->getValue());
    }

    public function testJsonUnserializeThrowsOnInvalidBase64(): void
    {
        $this->expectException(\DomainException::class);
        $decoded = new \stdClass();
        $decoded->Verb = 'get';
        $decoded->Key = 'k';
        $decoded->Value = '!!!invalid!!!';
        KVTxnOp::jsonUnserialize($decoded);
    }

    public function testConstructorWithInvalidStringVerbThrows(): void
    {
        $this->expectException(\ValueError::class);
        new KVTxnOp(Verb: 'not-a-valid-verb');
    }
}

