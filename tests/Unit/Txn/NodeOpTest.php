<?php

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\NodeOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class NodeOpTest extends TestCase
{
    public function testNodeGetValue(): void
    {
        self::assertSame('get', NodeOp::NodeGet->value);
    }

    public function testNodeSetValue(): void
    {
        self::assertSame('set', NodeOp::NodeSet->value);
    }

    public function testNodeCASValue(): void
    {
        self::assertSame('cas', NodeOp::NodeCAS->value);
    }

    public function testNodeDeleteValue(): void
    {
        self::assertSame('delete', NodeOp::NodeDelete->value);
    }

    public function testNodeDeleteCASValue(): void
    {
        self::assertSame('delete-cas', NodeOp::NodeDeleteCAS->value);
    }

    public function testUndefinedValue(): void
    {
        self::assertSame('', NodeOp::UNDEFINED->value);
    }

    public function testFromValidString(): void
    {
        self::assertSame(NodeOp::NodeGet, NodeOp::from('get'));
        self::assertSame(NodeOp::NodeSet, NodeOp::from('set'));
        self::assertSame(NodeOp::NodeCAS, NodeOp::from('cas'));
        self::assertSame(NodeOp::NodeDelete, NodeOp::from('delete'));
        self::assertSame(NodeOp::NodeDeleteCAS, NodeOp::from('delete-cas'));
        self::assertSame(NodeOp::UNDEFINED, NodeOp::from(''));
    }

    public function testFromInvalidStringThrows(): void
    {
        $this->expectException(\ValueError::class);
        NodeOp::from('not-valid');
    }

    public function testTryFromValidString(): void
    {
        self::assertSame(NodeOp::NodeGet, NodeOp::tryFrom('get'));
    }

    public function testTryFromInvalidStringReturnsNull(): void
    {
        self::assertNull(NodeOp::tryFrom('not-valid'));
    }

    public function testCasesReturnsAllValues(): void
    {
        $cases = NodeOp::cases();
        self::assertCount(6, $cases);
    }
}

