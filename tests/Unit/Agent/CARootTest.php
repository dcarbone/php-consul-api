<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\Agent\CARoot;
use function DCarbone\PHPConsulAPI\PHPLib\parse_time;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CARootTest extends TestCase
{
    public function testConstructorAndSetters(): void
    {
        $before = parse_time('2026-01-01T00:00:00Z');
        $after = parse_time('2027-01-01T00:00:00Z');
        $root = new CARoot(ID: 'id-1', Name: 'root-1', RootCertPEM: 'pem', Active: true, NotBefore: $before, NotAfter: $after, DaysRemaining: 365, CreateIndex: 1, ModifyIndex: 2);

        self::assertSame('id-1', $root->getID());
        self::assertSame('root-1', $root->Name);
        self::assertSame('pem', $root->getRootCertPEM());
        self::assertTrue($root->isActive());
        self::assertSame(365, $root->DaysRemaining);
        self::assertSame('1', (string)$root->getCreateIndex());
        self::assertSame('2', (string)$root->ModifyIndex);

        $result = $root
            ->setID('id-2')
            ->setName('root-2')
            ->setRootCertPEM('pem-2')
            ->setActive(false)
            ->setNotBefore($after)
            ->setNotAfter($before)
            ->setDaysRemaining(42)
            ->setCreateIndex(3)
            ->setModifyIndex(4);

        self::assertSame($root, $result);
        self::assertSame('id-2', $root->ID);
        self::assertSame('root-2', $root->getName());
        self::assertSame('pem-2', $root->RootCertPEM);
        self::assertFalse($root->Active);
        self::assertSame(42, $root->getDaysRemaining());
        self::assertSame('3', (string)$root->CreateIndex);
        self::assertSame('4', (string)$root->getModifyIndex());
    }

    public function testUint64FieldsAcceptIntStringAndBigInteger(): void
    {
        $root = new CARoot(CreateIndex: '18446744073709551615', ModifyIndex: BigInteger::of(7));
        self::assertSame('18446744073709551615', (string)$root->CreateIndex);
        self::assertSame('7', (string)$root->getModifyIndex());

        $root->setCreateIndex(5)->setModifyIndex('18446744073709551614');
        self::assertTrue($root->getCreateIndex()->isEqualTo(5));
        self::assertSame('18446744073709551614', (string)$root->ModifyIndex);

        $root->setCreateIndex(BigInteger::of('9223372036854775808'));
        self::assertSame('9223372036854775808', (string)$root->CreateIndex);

        $this->expectException(\InvalidArgumentException::class);
        $root->setModifyIndex(-1);
    }

    public function testJsonUnserializeLargeIndexes(): void
    {
        $root = CARoot::jsonUnserialize(json_decode('{"ID":"r","CreateIndex":18446744073709551615,"ModifyIndex":"9223372036854775808"}', flags: JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR));
        self::assertSame('18446744073709551615', (string)$root->CreateIndex);
        self::assertSame('9223372036854775808', (string)$root->ModifyIndex);

        $out = $root->jsonSerialize();
        self::assertSame($root->CreateIndex, $out->CreateIndex);
        self::assertSame('9223372036854775808', (string)$out->ModifyIndex);
    }

    public function testJsonUnserializeAndSerialize(): void
    {
        $obj = new \stdClass();
        $obj->ID = 'id-1';
        $obj->Name = 'root-1';
        $obj->RootCert = 'pem';
        $obj->Active = true;
        $obj->NotBefore = '2026-01-01T00:00:00Z';
        $obj->NotAfter = '2027-01-01T00:00:00Z';
        $obj->DaysRemaining = 365;
        $obj->CreateIndex = 1;
        $obj->ModifyIndex = 2;

        $root = CARoot::jsonUnserialize($obj);
        self::assertSame('id-1', $root->ID);
        self::assertSame('pem', $root->RootCertPEM);
        self::assertNotNull($root->NotBefore);

        $out = $root->jsonSerialize();
        self::assertSame('pem', $out->RootCert);
        self::assertSame(365, $out->DaysRemaining);
    }

    public function testNanosecondTimestampsRoundTrip(): void
    {
        $obj = new \stdClass();
        $obj->ID = 'id-1';
        $obj->NotBefore = '2026-04-03T20:02:59.892792592-05:00';
        $obj->NotAfter = '2027-01-01T00:00:00.000000001Z';

        $root = CARoot::jsonUnserialize($obj);
        self::assertNotNull($root->NotBefore);
        self::assertSame(892792592, $root->NotBefore->Nanosecond());

        $out = $root->jsonSerialize();
        self::assertSame('2026-04-03T20:02:59.892792592-05:00', $out->NotBefore);
        self::assertSame('2027-01-01T00:00:00.000000001Z', $out->NotAfter);
    }
}
