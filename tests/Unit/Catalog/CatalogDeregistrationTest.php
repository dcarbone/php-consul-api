<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CatalogDeregistrationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $d = new CatalogDeregistration();
        self::assertSame('', $d->getNode());
        self::assertSame('', $d->Node);
        self::assertSame('', $d->getAddress());
        self::assertSame('', $d->getDatacenter());
        self::assertSame('', $d->getServiceID());
        self::assertSame('', $d->getCheckID());
        self::assertSame('', $d->getNamespace());
        self::assertSame('', $d->getPartition());
    }

    public function testConstructorWithParams(): void
    {
        $d = new CatalogDeregistration(
            Node: 'n',
            Address: 'a',
            Datacenter: 'dc',
            ServiceID: 's',
            CheckID: 'c',
            Namespace: 'ns',
            Partition: 'pt',
        );
        self::assertSame('n', $d->getNode());
        self::assertSame('a', $d->getAddress());
        self::assertSame('dc', $d->getDatacenter());
        self::assertSame('s', $d->getServiceID());
        self::assertSame('c', $d->getCheckID());
        self::assertSame('ns', $d->getNamespace());
        self::assertSame('pt', $d->getPartition());
    }

    public function testFluentSetters(): void
    {
        $d = new CatalogDeregistration();
        $result = $d->setNode('n')
            ->setAddress('a')
            ->setDatacenter('dc')
            ->setServiceID('s')
            ->setCheckID('c')
            ->setNamespace('ns')
            ->setPartition('pt');
        self::assertSame($d, $result);
        self::assertSame('n', $d->getNode());
        self::assertSame('n', $d->Node);
        self::assertSame('a', $d->getAddress());
        self::assertSame('dc', $d->getDatacenter());
        self::assertSame('s', $d->getServiceID());
        self::assertSame('c', $d->getCheckID());
        self::assertSame('ns', $d->getNamespace());
        self::assertSame('pt', $d->getPartition());
    }

    public function testJsonSerialize(): void
    {
        $d = new CatalogDeregistration(Node: 'n', ServiceID: 's');
        $out = $d->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('n', $out->Node);
        self::assertSame('s', $out->ServiceID);
    }

    public function testJsonSerializeOmitsEmptyOptionalFields(): void
    {
        $d = new CatalogDeregistration(Node: 'n');
        $out = $d->jsonSerialize();
        self::assertObjectNotHasProperty('Address', $out);
        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
    }

    public function testJsonUnserialize(): void
    {
        $obj = new \stdClass();
        $obj->Node = 'n1';
        $obj->Address = 'a';
        $obj->Datacenter = 'dc';
        $obj->ServiceID = 's';
        $obj->CheckID = 'c';
        $obj->Namespace = 'ns';
        $obj->Partition = 'pt';
        $d = CatalogDeregistration::jsonUnserialize($obj);
        self::assertInstanceOf(CatalogDeregistration::class, $d);
        self::assertSame('n1', $d->getNode());
        self::assertSame('s', $d->getServiceID());
        self::assertSame('ns', $d->getNamespace());
    }
}

