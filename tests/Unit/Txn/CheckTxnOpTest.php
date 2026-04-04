<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Txn\CheckTxnOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CheckTxnOpTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $op = new CheckTxnOp();
        self::assertInstanceOf(HealthCheck::class, $op->getCheck());
    }

    public function testConstructorWithValues(): void
    {
        $check = new HealthCheck(Node: 'node-1', Name: 'my-check');
        $op = new CheckTxnOp(Check: $check);
        self::assertSame('node-1', $op->getCheck()->getNode());
        self::assertSame('my-check', $op->getCheck()->getName());
    }

    public function testFluentSetters(): void
    {
        $op = new CheckTxnOp();
        $check = new HealthCheck(Name: 'test');
        $result = $op->setCheck($check);
        self::assertSame($op, $result);
        self::assertSame('test', $op->getCheck()->getName());
    }

    public function testJsonSerialize(): void
    {
        $op = new CheckTxnOp(Check: new HealthCheck(Name: 'hc'));
        $out = $op->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
    }
}

