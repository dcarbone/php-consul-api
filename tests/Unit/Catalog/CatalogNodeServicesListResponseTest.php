<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\CatalogNodeServiceList;
use DCarbone\PHPConsulAPI\Catalog\CatalogNodeServicesListResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CatalogNodeServicesListResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new CatalogNodeServicesListResponse();
        self::assertNull($r->getValue());
        self::assertNull($r->CatalogNodeServiceList);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValueNull(): void
    {
        $r = new CatalogNodeServicesListResponse();
        $r->unmarshalValue(null);
        self::assertNull($r->getValue());
    }

    public function testUnmarshalValueWithData(): void
    {
        $d = new \stdClass();
        $nodeObj = new \stdClass();
        $nodeObj->ID = 'n1';
        $nodeObj->Node = 'node1';
        $nodeObj->Address = '';
        $nodeObj->Datacenter = '';
        $nodeObj->TaggedAddresses = new \stdClass();
        $nodeObj->Meta = new \stdClass();
        $nodeObj->CreateIndex = 0;
        $nodeObj->ModifyIndex = 0;
        $d->Node = $nodeObj;
        $d->Services = [];

        $r = new CatalogNodeServicesListResponse();
        $r->unmarshalValue($d);
        self::assertInstanceOf(CatalogNodeServiceList::class, $r->getValue());
    }

    public function testOffsetAccess(): void
    {
        $r = new CatalogNodeServicesListResponse();
        self::assertTrue(isset($r[0]));
        self::assertNull($r[0]);
    }
}

