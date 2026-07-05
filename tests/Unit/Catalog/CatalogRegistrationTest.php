<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Catalog\CatalogRegistration;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CatalogRegistrationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new CatalogRegistration();
        self::assertSame('', $r->getID());
        self::assertSame('', $r->ID);
        self::assertSame('', $r->getNode());
        self::assertSame('', $r->getAddress());
        self::assertSame('', $r->getDatacenter());
        self::assertNull($r->getService());
        self::assertNull($r->getCheck());
        self::assertFalse($r->isSkipNodeUpdate());
        self::assertSame('', $r->getPartition());
        self::assertNull($r->getLocality());
    }

    public function testConstructorWithParams(): void
    {
        $svc = new AgentService(ID: 'svc-1');
        $chk = new AgentCheck(CheckID: 'chk-1');
        $r = new CatalogRegistration(
            ID: 'id-1',
            Node: 'node1',
            Address: '10.0.0.1',
            Datacenter: 'dc1',
            Service: $svc,
            Check: $chk,
            SkipNodeUpdate: true,
            Partition: 'pt',
        );
        self::assertSame('id-1', $r->getID());
        self::assertSame('node1', $r->getNode());
        self::assertSame('10.0.0.1', $r->getAddress());
        self::assertSame('dc1', $r->getDatacenter());
        self::assertSame($svc, $r->getService());
        self::assertSame($chk, $r->getCheck());
        self::assertTrue($r->isSkipNodeUpdate());
        self::assertSame('pt', $r->getPartition());
    }

    public function testFluentSetters(): void
    {
        $r = new CatalogRegistration();
        $svc = new AgentService(ID: 'svc');
        $result = $r->setID('id')
            ->setNode('n')
            ->setAddress('a')
            ->setDatacenter('dc')
            ->setService($svc)
            ->setSkipNodeUpdate(true)
            ->setPartition('pt');
        self::assertSame($r, $result);
        self::assertSame('id', $r->getID());
        self::assertSame('id', $r->ID);
        self::assertSame('n', $r->getNode());
        self::assertSame($svc, $r->getService());
        self::assertTrue($r->isSkipNodeUpdate());
    }

    public function testJsonSerialize(): void
    {
        $r = new CatalogRegistration(ID: 'id', Node: 'n', Address: 'a');
        $out = $r->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('id', $out->ID);
        self::assertSame('n', $out->Node);
    }

    public function testJsonSerializeOmitsEmptyOptionalFields(): void
    {
        $r = new CatalogRegistration(ID: 'id', Node: 'n');
        $out = $r->jsonSerialize();
        self::assertObjectNotHasProperty('Partition', $out);
        self::assertObjectNotHasProperty('Locality', $out);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->ID = 'id';
        $d->Node = 'node';
        $d->Address = 'addr';
        $d->Datacenter = 'dc';
        $d->TaggedAddresses = new \stdClass();
        $d->NodeMeta = new \stdClass();
        $d->Service = null;
        $d->Check = null;
        $d->Checks = null;
        $d->SkipNodeUpdate = true;
        $r = CatalogRegistration::jsonUnserialize($d);
        self::assertInstanceOf(CatalogRegistration::class, $r);
        self::assertSame('id', $r->getID());
        self::assertSame('node', $r->getNode());
        self::assertTrue($r->isSkipNodeUpdate());
        self::assertNull($r->getService());
    }
}

