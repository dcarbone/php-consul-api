<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Txn\ServiceTxnOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceTxnOpTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $op = new ServiceTxnOp();
        self::assertSame('', $op->getNode());
        self::assertInstanceOf(AgentService::class, $op->getService());
    }

    public function testConstructorWithValues(): void
    {
        $svc = new AgentService(ID: 'svc-1', Service: 'web');
        $op = new ServiceTxnOp(Node: 'node-1', Service: $svc);
        self::assertSame('node-1', $op->getNode());
        self::assertSame('svc-1', $op->getService()->getID());
    }

    public function testFluentSetters(): void
    {
        $op = new ServiceTxnOp();
        $svc = new AgentService(ID: 'x');
        $result = $op->setNode('n')->setService($svc);
        self::assertSame($op, $result);
        self::assertSame('n', $op->getNode());
    }

    public function testJsonSerialize(): void
    {
        $op = new ServiceTxnOp(Node: 'n', Service: new AgentService(ID: 's'));
        $out = $op->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
    }
}

