<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

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
        self::assertSame(1, $leaf->CreateIndex);
        self::assertSame(2, $leaf->ModifyIndex);

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
        self::assertSame(3, $leaf->getCreateIndex());
        self::assertSame(4, $leaf->ModifyIndex);
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
