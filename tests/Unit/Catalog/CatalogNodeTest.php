<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Catalog\CatalogNode;
use DCarbone\PHPConsulAPI\Catalog\Node;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CatalogNodeTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $cn = new CatalogNode();
        self::assertNull($cn->getNode());
        self::assertSame([], $cn->getServices());
    }

    public function testConstructorWithParams(): void
    {
        $node = new Node(ID: 'n1', Node: 'node1');
        $svc = new AgentService(ID: 'svc-1', Service: 'web');
        $cn = new CatalogNode(Node: $node, Services: ['web' => $svc]);
        self::assertSame($node, $cn->getNode());
        self::assertCount(1, $cn->getServices());
        self::assertArrayHasKey('web', $cn->getServices());
        self::assertSame($svc, $cn->getServices()['web']);
    }

    public function testFluentSetters(): void
    {
        $cn = new CatalogNode();
        $node = new Node(ID: 'n1');
        $svc = new AgentService(ID: 'svc-1');
        $result = $cn->setNode($node)->setServices(['web' => $svc]);
        self::assertSame($cn, $result);
        self::assertSame($node, $cn->getNode());
        self::assertSame($node, $cn->Node);
        self::assertCount(1, $cn->getServices());
    }

    public function testJsonSerialize(): void
    {
        $node = new Node(ID: 'n1');
        $cn = new CatalogNode(Node: $node);
        $out = $cn->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame($node, $out->Node);
    }

    public function testJsonUnserialize(): void
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
        $d->Services = ['web' => $svcObj];

        $cn = CatalogNode::jsonUnserialize($d);
        self::assertInstanceOf(CatalogNode::class, $cn);
        self::assertInstanceOf(Node::class, $cn->getNode());
        self::assertSame('n1', $cn->getNode()->getID());
        self::assertCount(1, $cn->getServices());
        self::assertInstanceOf(AgentService::class, $cn->getServices()['web']);
    }
}

