<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\CatalogNode;
use DCarbone\PHPConsulAPI\Catalog\CatalogNodeResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CatalogNodeResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new CatalogNodeResponse();
        self::assertNull($r->getValue());
        self::assertNull($r->Node);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValueNull(): void
    {
        $r = new CatalogNodeResponse();
        $r->unmarshalValue(null);
        self::assertNull($r->getValue());
    }

    public function testUnmarshalValueWithData(): void
    {
        $d = new \stdClass();
        $nodeObj = new \stdClass();
        $nodeObj->ID = 'n1';
        $nodeObj->Node = 'node1';
        $nodeObj->Address = 'addr';
        $nodeObj->Datacenter = '';
        $nodeObj->TaggedAddresses = new \stdClass();
        $nodeObj->Meta = new \stdClass();
        $nodeObj->CreateIndex = 0;
        $nodeObj->ModifyIndex = 0;
        $d->Node = $nodeObj;
        $d->Services = [];

        $r = new CatalogNodeResponse();
        $r->unmarshalValue($d);
        self::assertInstanceOf(CatalogNode::class, $r->getValue());
        self::assertSame('n1', $r->getValue()->getNode()->getID());
    }

    public function testOffsetAccess(): void
    {
        $r = new CatalogNodeResponse();
        self::assertTrue(isset($r[0]));
        self::assertTrue(isset($r[1]));
        self::assertTrue(isset($r[2]));
        self::assertFalse(isset($r[3]));
        self::assertNull($r[0]);
    }
}
