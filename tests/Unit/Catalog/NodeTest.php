<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\Node;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class NodeTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $n = new Node();
        self::assertSame('', $n->getID());
        self::assertSame('', $n->ID);
        self::assertSame('', $n->getNode());
        self::assertSame('', $n->getAddress());
        self::assertSame('', $n->getDatacenter());
        self::assertSame(0, $n->getCreateIndex());
        self::assertSame(0, $n->getModifyIndex());
        self::assertSame('', $n->getPartition());
        self::assertSame('', $n->getPeerName());
        self::assertNull($n->getLocality());
    }

    public function testConstructorWithParams(): void
    {
        $n = new Node(
            ID: 'id-1',
            Node: 'node1',
            Address: '10.0.0.1',
            Datacenter: 'dc1',
            CreateIndex: 1,
            ModifyIndex: 2,
            Partition: 'pt',
            PeerName: 'peer1',
        );
        self::assertSame('id-1', $n->getID());
        self::assertSame('node1', $n->getNode());
        self::assertSame('10.0.0.1', $n->getAddress());
        self::assertSame('dc1', $n->getDatacenter());
        self::assertSame(1, $n->getCreateIndex());
        self::assertSame(2, $n->getModifyIndex());
        self::assertSame('pt', $n->getPartition());
        self::assertSame('peer1', $n->getPeerName());
    }

    public function testFluentSetters(): void
    {
        $n = new Node();
        $result = $n->setID('id')
            ->setNode('n')
            ->setAddress('a')
            ->setDatacenter('dc')
            ->setCreateIndex(1)
            ->setModifyIndex(2)
            ->setPartition('pt')
            ->setPeerName('p');
        self::assertSame($n, $result);
        self::assertSame('id', $n->getID());
        self::assertSame('id', $n->ID);
        self::assertSame('n', $n->getNode());
        self::assertSame('a', $n->getAddress());
        self::assertSame('dc', $n->getDatacenter());
        self::assertSame('pt', $n->getPartition());
        self::assertSame('p', $n->getPeerName());
    }

    public function testJsonSerialize(): void
    {
        $n = new Node(ID: 'x', Node: 'y', Address: 'z');
        $out = $n->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertSame('y', $out->Node);
        self::assertSame('z', $out->Address);
    }

    public function testJsonSerializeOmitsEmptyOptionalFields(): void
    {
        $n = new Node(ID: 'x', Node: 'y');
        $out = $n->jsonSerialize();
        self::assertObjectNotHasProperty('Partition', $out);
        self::assertObjectNotHasProperty('PeerName', $out);
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
        $d->Meta = new \stdClass();
        $d->CreateIndex = 5;
        $d->ModifyIndex = 10;
        $n = Node::jsonUnserialize($d);
        self::assertInstanceOf(Node::class, $n);
        self::assertSame('id', $n->getID());
        self::assertSame('node', $n->getNode());
        self::assertSame(5, $n->getCreateIndex());
    }
}

