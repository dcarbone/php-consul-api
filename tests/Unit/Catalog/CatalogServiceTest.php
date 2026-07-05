<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\CatalogService;
use DCarbone\PHPConsulAPI\Catalog\Weights;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CatalogServiceTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new CatalogService();
        self::assertSame('', $s->getID());
        self::assertSame('', $s->ID);
        self::assertSame('', $s->getNode());
        self::assertSame('', $s->getAddress());
        self::assertSame('', $s->getDatacenter());
        self::assertSame('', $s->getServiceID());
        self::assertSame('', $s->getServiceName());
        self::assertSame('', $s->getServiceAddress());
        self::assertSame(0, $s->getServicePort());
        self::assertSame([], $s->getServiceTags());
        self::assertInstanceOf(Weights::class, $s->getServiceWeights());
        self::assertFalse($s->isServiceEnableTagOverride());
        self::assertNull($s->getServiceProxy());
        self::assertNull($s->getServiceLocality());
        self::assertSame(0, $s->getCreateIndex());
        self::assertSame(0, $s->getModifyIndex());
        self::assertInstanceOf(HealthChecks::class, $s->getChecks());
        self::assertSame('', $s->getNamespace());
        self::assertSame('', $s->getPartition());
    }

    public function testConstructorWithParams(): void
    {
        $s = new CatalogService(
            ID: 'id',
            Node: 'node1',
            Address: '10.0.0.1',
            Datacenter: 'dc1',
            ServiceID: 'web-1',
            ServiceName: 'web',
            ServicePort: 8080,
            ServiceTags: ['v1'],
            CreateIndex: 1,
            ModifyIndex: 2,
            Namespace: 'ns',
            Partition: 'pt',
        );
        self::assertSame('id', $s->getID());
        self::assertSame('web-1', $s->getServiceID());
        self::assertSame('web', $s->getServiceName());
        self::assertSame(8080, $s->getServicePort());
        self::assertSame(['v1'], $s->getServiceTags());
        self::assertSame(1, $s->getCreateIndex());
        self::assertSame('ns', $s->getNamespace());
    }

    public function testFluentSetters(): void
    {
        $s = new CatalogService();
        $w = new Weights(Passing: 10);
        $result = $s->setID('id')
            ->setNode('n')
            ->setAddress('a')
            ->setDatacenter('dc')
            ->setServiceID('sid')
            ->setServiceName('sn')
            ->setServiceAddress('sa')
            ->setServicePort(80)
            ->setServiceTags('v1', 'v2')
            ->setServiceWeights($w)
            ->setServiceEnableTagOverride(true)
            ->setCreateIndex(1)
            ->setModifyIndex(2)
            ->setNamespace('ns')
            ->setPartition('pt');
        self::assertSame($s, $result);
        self::assertSame('id', $s->getID());
        self::assertSame('id', $s->ID);
        self::assertSame('sid', $s->getServiceID());
        self::assertSame(['v1', 'v2'], $s->getServiceTags());
        self::assertSame($w, $s->getServiceWeights());
        self::assertTrue($s->isServiceEnableTagOverride());
    }

    public function testJsonSerialize(): void
    {
        $s = new CatalogService(ServiceID: 'web', ServiceName: 'web', ServicePort: 80);
        $out = $s->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('web', $out->ServiceID);
        self::assertSame(80, $out->ServicePort);
    }

    public function testJsonSerializeOmitsEmptyOptionalFields(): void
    {
        $s = new CatalogService(ServiceID: 'web');
        $out = $s->jsonSerialize();
        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
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
        $d->ServiceID = 'web-1';
        $d->ServiceName = 'web';
        $d->ServiceAddress = '';
        $d->ServiceTags = ['v1'];
        $d->ServiceMeta = new \stdClass();
        $d->ServicePort = 8080;
        $wObj = new \stdClass();
        $wObj->Passing = 10;
        $wObj->Warning = 1;
        $d->ServiceWeights = $wObj;
        $d->ServiceEnableTagOverride = false;
        $d->CreateIndex = 1;
        $d->ModifyIndex = 2;

        $s = CatalogService::jsonUnserialize($d);
        self::assertInstanceOf(CatalogService::class, $s);
        self::assertSame('web-1', $s->getServiceID());
        self::assertSame('web', $s->getServiceName());
        self::assertSame(8080, $s->getServicePort());
        self::assertSame(['v1'], $s->getServiceTags());
        self::assertSame(10, $s->getServiceWeights()->getPassing());
    }
}

