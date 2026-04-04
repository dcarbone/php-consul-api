<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Catalog\Node;
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
        self::assertInstanceOf(Node::class, $op->getNode());
    }

    public function testConstructorWithValues(): void
    {
        $node = new Node(Node: 'node-1', Address: '10.0.0.1');
        $op = new NodeTxnOp(Node: $node);
        self::assertSame('node-1', $op->getNode()->getNode());
        self::assertSame('10.0.0.1', $op->getNode()->getAddress());
    }

    public function testFluentSetters(): void
    {
        $op = new NodeTxnOp();
        $node = new Node(Node: 'n');
        $result = $op->setNode($node);
        self::assertSame($op, $result);
        self::assertSame('n', $op->getNode()->getNode());
    }

    public function testJsonSerialize(): void
    {
        $op = new NodeTxnOp(Node: new Node(Node: 'x'));
        $out = $op->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
    }
}

