<?php

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Txn\NodeOp;
use DCarbone\PHPConsulAPI\Txn\NodeTxnOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class NodeTxnOpTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $op = new NodeTxnOp();
        self::assertSame(NodeOp::UNDEFINED, $op->getVerb());
        self::assertSame(NodeOp::UNDEFINED, $op->Verb);
        self::assertInstanceOf(Node::class, $op->getNode());
        self::assertInstanceOf(Node::class, $op->Node);
    }

    public function testConstructorWithEnumVerb(): void
    {
        $node = new Node(Node: 'node-1', Address: '10.0.0.1');
        $op = new NodeTxnOp(Verb: NodeOp::NodeGet, Node: $node);
        self::assertSame(NodeOp::NodeGet, $op->getVerb());
        self::assertSame('node-1', $op->getNode()->getNode());
        self::assertSame('10.0.0.1', $op->getNode()->getAddress());
    }

    public function testConstructorWithStringVerb(): void
    {
        $op = new NodeTxnOp(Verb: 'set');
        self::assertSame(NodeOp::NodeSet, $op->getVerb());
    }

    public function testConstructorWithInvalidStringVerbThrows(): void
    {
        $this->expectException(\ValueError::class);
        new NodeTxnOp(Verb: 'not-valid');
    }

    public function testSetVerbWithString(): void
    {
        $op = new NodeTxnOp();
        $result = $op->setVerb('cas');
        self::assertSame($op, $result);
        self::assertSame(NodeOp::NodeCAS, $op->getVerb());
    }

    public function testSetVerbWithEnum(): void
    {
        $op = new NodeTxnOp();
        $op->setVerb(NodeOp::NodeDelete);
        self::assertSame(NodeOp::NodeDelete, $op->getVerb());
    }

    public function testFluentSetters(): void
    {
        $op = new NodeTxnOp();
        $node = new Node(Node: 'n');
        $result = $op
            ->setVerb(NodeOp::NodeSet)
            ->setNode($node);
        self::assertSame($op, $result);
        self::assertSame(NodeOp::NodeSet, $op->getVerb());
        self::assertSame('n', $op->getNode()->getNode());
    }

    public function testJsonSerialize(): void
    {
        $op = new NodeTxnOp(Verb: NodeOp::NodeGet, Node: new Node(Node: 'x'));
        $out = $op->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(NodeOp::NodeGet, $out->Verb);
    }

    public function testJsonUnserialize(): void
    {
        $nodeObj = new \stdClass();
        $nodeObj->Node = 'n1';
        $nodeObj->Address = '10.0.0.1';
        $nodeObj->Datacenter = '';
        $nodeObj->TaggedAddresses = new \stdClass();
        $nodeObj->Meta = new \stdClass();
        $nodeObj->CreateIndex = 1;
        $nodeObj->ModifyIndex = 2;
        $nodeObj->ID = '';
        $nodeObj->Partition = '';
        $nodeObj->PeerName = '';

        $decoded = new \stdClass();
        $decoded->Verb = 'get';
        $decoded->Node = $nodeObj;

        $op = NodeTxnOp::jsonUnserialize($decoded);
        self::assertSame(NodeOp::NodeGet, $op->getVerb());
        self::assertSame('n1', $op->getNode()->getNode());
        self::assertSame('10.0.0.1', $op->getNode()->getAddress());
    }

    public function testJsonRoundTrip(): void
    {
        $op = new NodeTxnOp(Verb: NodeOp::NodeCAS, Node: new Node(Node: 'rt', Address: '1.2.3.4'));
        $decoded = json_decode(json_encode($op));
        $restored = NodeTxnOp::jsonUnserialize($decoded);
        self::assertSame(NodeOp::NodeCAS, $restored->getVerb());
        self::assertSame('rt', $restored->getNode()->getNode());
    }
}

