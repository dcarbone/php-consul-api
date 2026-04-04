<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\CatalogService;
use DCarbone\PHPConsulAPI\Catalog\CatalogServicesResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CatalogServicesResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new CatalogServicesResponse();
        self::assertSame([], $r->getValue());
        self::assertSame([], $r->Services);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValue(): void
    {
        $svcObj = new \stdClass();
        $svcObj->ID = 'id';
        $svcObj->Node = 'node';
        $svcObj->Address = '';
        $svcObj->Datacenter = '';
        $svcObj->TaggedAddresses = new \stdClass();
        $svcObj->NodeMeta = new \stdClass();
        $svcObj->ServiceID = 'web-1';
        $svcObj->ServiceName = 'web';
        $svcObj->ServiceAddress = '';
        $svcObj->ServiceTags = [];
        $svcObj->ServiceMeta = new \stdClass();
        $svcObj->ServicePort = 80;
        $wObj = new \stdClass();
        $wObj->Passing = 0;
        $wObj->Warning = 0;
        $svcObj->ServiceWeights = $wObj;
        $svcObj->ServiceEnableTagOverride = false;
        $svcObj->CreateIndex = 0;
        $svcObj->ModifyIndex = 0;

        $r = new CatalogServicesResponse();
        $r->unmarshalValue([$svcObj]);
        self::assertCount(1, $r->getValue());
        self::assertInstanceOf(CatalogService::class, $r->getValue()[0]);
        self::assertSame('web-1', $r->getValue()[0]->getServiceID());
    }

    public function testOffsetAccess(): void
    {
        $r = new CatalogServicesResponse();
        self::assertTrue(isset($r[0]));
        self::assertSame([], $r[0]);
    }
}

