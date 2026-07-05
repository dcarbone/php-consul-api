<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

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
        self::assertSame(1, $root->getCreateIndex());
        self::assertSame(2, $root->ModifyIndex);

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
        self::assertSame(3, $root->CreateIndex);
        self::assertSame(4, $root->getModifyIndex());
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
}
