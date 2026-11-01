<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use Brick\Math\BigInteger;
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
        self::assertTrue($op->getFlags()->isZero());
        self::assertTrue($op->getIndex()->isZero());
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
        self::assertSame('5', (string)$op->getFlags());
        self::assertSame('10', (string)$op->getIndex());
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
        self::assertTrue($op->Flags->isZero());
        self::assertTrue($op->Index->isZero());
    }

    public function testUint64FieldsAcceptIntStringAndBigInteger(): void
    {
        $op = new KVTxnOp(Flags: '18446744073709551615', Index: BigInteger::of(3));
        self::assertSame('18446744073709551615', (string)$op->Flags);
        self::assertSame('3', (string)$op->getIndex());

        $op = new KVTxnOp(Flags: 1, Index: '9223372036854775808');
        self::assertSame('1', (string)$op->getFlags());
        self::assertSame('9223372036854775808', (string)$op->Index);

        $op->setFlags(BigInteger::of(2))->setIndex(4);
        self::assertTrue($op->Flags->isEqualTo(2));
        self::assertTrue($op->Index->isEqualTo(4));

        $op->setFlags('18446744073709551615');
        self::assertSame('18446744073709551615', (string)$op->getFlags());

        $out = $op->jsonSerialize();
        self::assertSame($op->Flags, $out->Flags);
        self::assertSame($op->Index, $out->Index);
    }

    public function testJsonUnserializeLargeValues(): void
    {
        $op = KVTxnOp::jsonUnserialize(json_decode('{"Verb":"cas","Key":"k","Flags":18446744073709551615,"Index":"9223372036854775808"}', flags: JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR));
        self::assertSame('18446744073709551615', (string)$op->Flags);
        self::assertSame('9223372036854775808', (string)$op->Index);
    }

    public function testSetFlagsRejectsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new KVTxnOp())->setFlags('18446744073709551616');
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

