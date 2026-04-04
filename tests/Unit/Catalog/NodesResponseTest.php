<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Catalog\NodesResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class NodesResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new NodesResponse();
        self::assertSame([], $r->getValue());
        self::assertSame([], $r->Nodes);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValue(): void
    {
        $nodeObj = new \stdClass();
        $nodeObj->ID = 'n1';
        $nodeObj->Node = 'node1';
        $nodeObj->Address = '10.0.0.1';
        $nodeObj->Datacenter = 'dc1';
        $nodeObj->TaggedAddresses = new \stdClass();
        $nodeObj->Meta = new \stdClass();
        $nodeObj->CreateIndex = 1;
        $nodeObj->ModifyIndex = 2;

        $r = new NodesResponse();
        $r->unmarshalValue([$nodeObj]);
        self::assertCount(1, $r->getValue());
        self::assertInstanceOf(Node::class, $r->getValue()[0]);
        self::assertSame('n1', $r->getValue()[0]->getID());
        self::assertSame('node1', $r->getValue()[0]->getNode());
    }

    public function testOffsetAccess(): void
    {
        $r = new NodesResponse();
        self::assertTrue(isset($r[0]));
        self::assertSame([], $r[0]);
    }
}

