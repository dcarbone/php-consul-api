<?php


namespace DCarbone\PHPConsulAPITests\Unit\KV;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\KV\KVPair;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KVPairTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $kvp = new KVPair();
        self::assertSame('', $kvp->getKey());
        self::assertSame('', $kvp->Key);
        self::assertSame('0', (string)$kvp->getCreateIndex());
        self::assertSame('0', (string)$kvp->CreateIndex);
        self::assertSame('0', (string)$kvp->getModifyIndex());
        self::assertSame('0', (string)$kvp->ModifyIndex);
        self::assertSame('0', (string)$kvp->getLockIndex());
        self::assertSame('0', (string)$kvp->LockIndex);
        self::assertSame('0', (string)$kvp->getFlags());
        self::assertSame('0', (string)$kvp->Flags);
        self::assertSame('', $kvp->getValue());
        self::assertSame('', $kvp->Value);
        self::assertSame('', $kvp->getSession());
        self::assertSame('', $kvp->Session);
        self::assertSame('', $kvp->getNamespace());
        self::assertSame('', $kvp->Namespace);
        self::assertSame('', $kvp->getPartition());
        self::assertSame('', $kvp->Partition);
    }

    public function testConstructorWithNamedParams(): void
    {
        $kvp = new KVPair(
            Key: 'test/key',
            CreateIndex: 10,
            ModifyIndex: '20',
            LockIndex: BigInteger::of(3),
            Flags: '18446744073709551615',
            Value: 'hello',
            Session: 'sess-1',
            Namespace: 'ns',
            Partition: 'part',
        );
        self::assertSame('test/key', $kvp->getKey());
        self::assertSame('test/key', $kvp->Key);
        self::assertSame('10', (string)$kvp->getCreateIndex());
        self::assertSame('10', (string)$kvp->CreateIndex);
        self::assertSame('20', (string)$kvp->getModifyIndex());
        self::assertSame('20', (string)$kvp->ModifyIndex);
        self::assertSame('3', (string)$kvp->getLockIndex());
        self::assertSame('3', (string)$kvp->LockIndex);
        self::assertSame('18446744073709551615', (string)$kvp->getFlags());
        self::assertSame('18446744073709551615', (string)$kvp->Flags);
        self::assertSame('hello', $kvp->getValue());
        self::assertSame('hello', $kvp->Value);
        self::assertSame('sess-1', $kvp->getSession());
        self::assertSame('sess-1', $kvp->Session);
        self::assertSame('ns', $kvp->getNamespace());
        self::assertSame('ns', $kvp->Namespace);
        self::assertSame('part', $kvp->getPartition());
        self::assertSame('part', $kvp->Partition);
    }

    public function testFluentSetters(): void
    {
        $kvp = new KVPair();
        $result = $kvp
            ->setKey('k')
            ->setCreateIndex(1)
            ->setModifyIndex('2')
            ->setLockIndex(BigInteger::of(3))
            ->setFlags('18446744073709551615')
            ->setValue('v')
            ->setSession('s')
            ->setNamespace('ns')
            ->setPartition('pt');

        self::assertSame($kvp, $result);
        self::assertSame('k', $kvp->getKey());
        self::assertSame('k', $kvp->Key);
        self::assertSame('1', (string)$kvp->getCreateIndex());
        self::assertSame('1', (string)$kvp->CreateIndex);
        self::assertSame('2', (string)$kvp->getModifyIndex());
        self::assertSame('2', (string)$kvp->ModifyIndex);
        self::assertSame('3', (string)$kvp->getLockIndex());
        self::assertSame('3', (string)$kvp->LockIndex);
        self::assertSame('18446744073709551615', (string)$kvp->getFlags());
        self::assertSame('18446744073709551615', (string)$kvp->Flags);
        self::assertSame('v', $kvp->getValue());
        self::assertSame('v', $kvp->Value);
        self::assertSame('s', $kvp->getSession());
        self::assertSame('s', $kvp->Session);
        self::assertSame('ns', $kvp->getNamespace());
        self::assertSame('ns', $kvp->Namespace);
        self::assertSame('pt', $kvp->getPartition());
        self::assertSame('pt', $kvp->Partition);
    }

    public function testJsonSerialize(): void
    {
        $kvp = new KVPair(Key: 'foo', Flags: 1, Value: 'bar');
        $out = $kvp->jsonSerialize();

        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('foo', $out->Key);
        self::assertSame('bar', $out->Value);
        self::assertInstanceOf(BigInteger::class, $out->Flags);
        self::assertSame('1', (string)$out->Flags);
    }

    public function testInvalidUint64Rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new KVPair(Flags: -1);
    }

    public function testSetterRejectsOverflow(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new KVPair())->setModifyIndex('18446744073709551616');
    }

    public function testJsonSerializeOmitsEmptyNamespaceAndPartition(): void
    {
        $kvp = new KVPair(Key: 'foo');
        $out = $kvp->jsonSerialize();

        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
    }

    public function testJsonSerializeIncludesNonEmptyNamespaceAndPartition(): void
    {
        $kvp = new KVPair(Key: 'foo', Namespace: 'ns1', Partition: 'pt1');
        $out = $kvp->jsonSerialize();

        self::assertSame('ns1', $out->Namespace);
        self::assertSame('pt1', $out->Partition);
    }

    public function testJsonUnserializeBase64DecodesValue(): void
    {
        $decoded = new \stdClass();
        $decoded->Key = 'test';
        $decoded->Value = base64_encode('hello world');
        $decoded->CreateIndex = 10;
        $decoded->ModifyIndex = '18446744073709551615';
        $decoded->LockIndex = 0;
        $decoded->Flags = 0;
        $decoded->Session = '';

        $kvp = KVPair::jsonUnserialize($decoded);

        self::assertSame('test', $kvp->getKey());
        self::assertSame('hello world', $kvp->getValue());
        self::assertSame('10', (string)$kvp->getCreateIndex());
        self::assertSame('18446744073709551615', (string)$kvp->getModifyIndex());
    }

    public function testJsonUnserializeThrowsOnInvalidBase64(): void
    {
        $this->expectException(\DomainException::class);

        $decoded = new \stdClass();
        // invalid base64 - a single "=" alone is not valid base64
        $decoded->Value = '!!!not-valid-base64!!!';
        $decoded->Key = 'k';

        KVPair::jsonUnserialize($decoded);
    }

    public function testDynamicFieldAssignment(): void
    {
        $kvp = new KVPair();
        $kvp->CustomField = 'custom-value';

        self::assertSame('custom-value', $kvp->CustomField);
        self::assertArrayHasKey('CustomField', $kvp->_getDynamicFields());
    }

    public function testDynamicFieldsIncludedInJsonSerialize(): void
    {
        $kvp = new KVPair(Key: 'k');
        $kvp->ExtraField = 'extra';

        $out = $kvp->jsonSerialize();
        self::assertSame('extra', $out->ExtraField);
    }

    public function testToString(): void
    {
        $kvp = new KVPair(Value: 'my-value');
        self::assertSame('my-value', (string)$kvp);
    }

    public function testJsonRoundTrip(): void
    {
        $original = new KVPair(
            Key: 'round/trip',
            CreateIndex: 5,
            ModifyIndex: 10,
            LockIndex: 1,
            Flags: 99,
            Value: 'test data',
            Session: 'sess-abc',
        );

        $serialized = $original->jsonSerialize();

        // Simulate what consul returns: Value is base64 encoded
        $serialized->Value = base64_encode($serialized->Value);

        $restored = KVPair::jsonUnserialize($serialized);

        self::assertSame($original->getKey(), $restored->getKey());
        self::assertTrue($original->getCreateIndex()->isEqualTo($restored->getCreateIndex()));
        self::assertTrue($original->getModifyIndex()->isEqualTo($restored->getModifyIndex()));
        self::assertTrue($original->getLockIndex()->isEqualTo($restored->getLockIndex()));
        self::assertTrue($original->getFlags()->isEqualTo($restored->getFlags()));
        self::assertSame($original->getValue(), $restored->getValue());
        self::assertSame($original->getSession(), $restored->getSession());
    }
}

