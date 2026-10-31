<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\Agent\LeafCert;
use function DCarbone\PHPConsulAPI\PHPLib\parse_time;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LeafCertTest extends TestCase
{
    public function testConstructorAndSetters(): void
    {
        $after = parse_time('2026-01-01T00:00:00Z');
        $before = parse_time('2027-01-01T00:00:00Z');
        $leaf = new LeafCert(SerialNumber: '123', CertPEM: 'cert', PrivateKeyPEM: 'key', Service: 'web', ServiceURI: 'spiffe://web', ValidAfter: $after, ValidBefore: $before, CreateIndex: 1, ModifyIndex: 2);

        self::assertSame('123', $leaf->getSerialNumber());
        self::assertSame('cert', $leaf->CertPEM);
        self::assertSame('key', $leaf->getPrivateKeyPEM());
        self::assertSame('web', $leaf->Service);
        self::assertSame('spiffe://web', $leaf->getServiceURI());
        self::assertSame('1', (string)$leaf->CreateIndex);
        self::assertSame('2', (string)$leaf->ModifyIndex);

        $result = $leaf
            ->setSerialNumber('456')
            ->setCertPEM('cert-2')
            ->setPrivateKeyPEM('key-2')
            ->setService('api')
            ->setServiceURI('spiffe://api')
            ->setValidAfter($before)
            ->setValidBefore($after)
            ->setCreateIndex(3)
            ->setModifyIndex(4);

        self::assertSame($leaf, $result);
        self::assertSame('456', $leaf->SerialNumber);
        self::assertSame('api', $leaf->getService());
        self::assertSame('3', (string)$leaf->getCreateIndex());
        self::assertSame('4', (string)$leaf->ModifyIndex);
    }

    public function testUint64FieldsAcceptIntStringAndBigInteger(): void
    {
        $leaf = new LeafCert(CreateIndex: '18446744073709551615', ModifyIndex: BigInteger::of(7));
        self::assertSame('18446744073709551615', (string)$leaf->CreateIndex);
        self::assertSame('7', (string)$leaf->getModifyIndex());

        $leaf->setCreateIndex(BigInteger::of(5))->setModifyIndex('9223372036854775808');
        self::assertTrue($leaf->getCreateIndex()->isEqualTo(5));
        self::assertSame('9223372036854775808', (string)$leaf->ModifyIndex);

        $leaf->setModifyIndex(11);
        self::assertSame('11', (string)$leaf->ModifyIndex);
    }

    public function testJsonUnserializeLargeIndexes(): void
    {
        $leaf = LeafCert::jsonUnserialize(json_decode('{"SerialNumber":"1","CreateIndex":18446744073709551615,"ModifyIndex":3}', flags: JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR));
        self::assertSame('18446744073709551615', (string)$leaf->CreateIndex);
        self::assertSame('3', (string)$leaf->ModifyIndex);

        $out = $leaf->jsonSerialize();
        self::assertSame('18446744073709551615', (string)$out->CreateIndex);
    }

    public function testJsonUnserializeAndSerialize(): void
    {
        $obj = new \stdClass();
        $obj->SerialNumber = '123';
        $obj->CertPEM = 'cert';
        $obj->PrivateKeyPEM = 'key';
        $obj->Service = 'web';
        $obj->ServiceURI = 'spiffe://web';
        $obj->ValidAfter = '2026-01-01T00:00:00Z';
        $obj->ValidBefore = '2027-01-01T00:00:00Z';
        $obj->CreateIndex = 1;
        $obj->ModifyIndex = 2;

        $leaf = LeafCert::jsonUnserialize($obj);
        self::assertSame('123', $leaf->SerialNumber);
        self::assertSame('web', $leaf->Service);
        self::assertNotNull($leaf->ValidAfter);

        $out = $leaf->jsonSerialize();
        self::assertSame('cert', $out->CertPEM);
        self::assertSame('spiffe://web', $out->ServiceURI);
    }
}
