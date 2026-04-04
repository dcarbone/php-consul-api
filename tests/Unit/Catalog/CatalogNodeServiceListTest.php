<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Catalog\CatalogNodeServiceList;
use DCarbone\PHPConsulAPI\Catalog\Node;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CatalogNodeServiceListTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $l = new CatalogNodeServiceList();
        self::assertNull($l->getNode());
        self::assertSame([], $l->getServices());
    }

    public function testConstructorWithParams(): void
    {
        $node = new Node(ID: 'n1');
        $svc = new AgentService(ID: 'svc-1', Service: 'web');
        $l = new CatalogNodeServiceList(Node: $node, Services: [$svc]);
        self::assertSame($node, $l->getNode());
        self::assertSame($node, $l->Node);
        self::assertCount(1, $l->getServices());
        self::assertSame($svc, $l->getServices()[0]);
    }

    public function testFluentSetters(): void
    {
        $l = new CatalogNodeServiceList();
        $node = new Node(ID: 'n1');
        $svc = new AgentService(ID: 'svc-1');
        $result = $l->setNode($node)->setServices($svc);
        self::assertSame($l, $result);
        self::assertSame($node, $l->getNode());
        self::assertCount(1, $l->getServices());
    }

    public function testJsonSerialize(): void
    {
        $node = new Node(ID: 'n1');
        $l = new CatalogNodeServiceList(Node: $node);
        $out = $l->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame($node, $out->Node);
    }

    public function testJsonUnserialize(): void
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

        $svcObj = new \stdClass();
        $svcObj->Kind = '';
        $svcObj->ID = 'svc-1';
        $svcObj->Service = 'web';
        $svcObj->Port = 80;
        $svcObj->Address = '';
        $svcObj->Tags = [];
        $svcObj->Meta = new \stdClass();
        $svcObj->CreateIndex = 0;
        $svcObj->ModifyIndex = 0;
        $d->Services = [$svcObj];

        $l = CatalogNodeServiceList::jsonUnserialize($d);
        self::assertInstanceOf(CatalogNodeServiceList::class, $l);
        self::assertInstanceOf(Node::class, $l->getNode());
        self::assertCount(1, $l->getServices());
        self::assertInstanceOf(AgentService::class, $l->getServices()[0]);
    }
}

