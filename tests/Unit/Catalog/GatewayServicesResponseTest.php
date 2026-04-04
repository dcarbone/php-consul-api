<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\GatewayService;
use DCarbone\PHPConsulAPI\Catalog\GatewayServicesResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GatewayServicesResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new GatewayServicesResponse();
        self::assertSame([], $r->getValue());
        self::assertSame([], $r->GatewayServices);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValue(): void
    {
        $gwObj = new \stdClass();
        $gwObj->Name = 'gw';
        $gwObj->Namespace = '';
        $gwObj->Partition = '';
        $svcObj = new \stdClass();
        $svcObj->Name = 'web';
        $svcObj->Namespace = '';
        $svcObj->Partition = '';

        $d = new \stdClass();
        $d->Gateway = $gwObj;
        $d->Service = $svcObj;
        $d->GatewayKind = '';
        $d->Port = 0;
        $d->Protocol = '';
        $d->Hosts = [];
        $d->CAFile = '';
        $d->CertFile = '';
        $d->KeyFile = '';
        $d->SNI = '';
        $d->FromWildCard = '';

        $r = new GatewayServicesResponse();
        $r->unmarshalValue([$d]);
        self::assertCount(1, $r->getValue());
        self::assertInstanceOf(GatewayService::class, $r->getValue()[0]);
        self::assertSame('gw', $r->getValue()[0]->getGateway()->getName());
    }

    public function testOffsetAccess(): void
    {
        $r = new GatewayServicesResponse();
        self::assertTrue(isset($r[0]));
        self::assertSame([], $r[0]);
    }
}

