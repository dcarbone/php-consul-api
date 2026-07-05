<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLNodeIdentityTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $n = new ACLNodeIdentity();
        self::assertSame('', $n->getNodeName());
        self::assertSame('', $n->NodeName);
        self::assertSame('', $n->getDatacenter());
        self::assertSame('', $n->Datacenter);
    }

    public function testConstructorWithParams(): void
    {
        $n = new ACLNodeIdentity(NodeName: 'node-1', Datacenter: 'dc1');
        self::assertSame('node-1', $n->getNodeName());
        self::assertSame('node-1', $n->NodeName);
        self::assertSame('dc1', $n->getDatacenter());
        self::assertSame('dc1', $n->Datacenter);
    }

    public function testFluentSetters(): void
    {
        $n = new ACLNodeIdentity();
        $result = $n->setNodeName('node-set')->setDatacenter('dc-set');
        self::assertSame($n, $result);
        self::assertSame('node-set', $n->getNodeName());
        self::assertSame('node-set', $n->NodeName);
        self::assertSame('dc-set', $n->getDatacenter());
        self::assertSame('dc-set', $n->Datacenter);
    }

    public function testJsonSerialize(): void
    {
        $n = new ACLNodeIdentity(NodeName: 'n', Datacenter: 'd');
        $out = $n->jsonSerialize();
        self::assertSame('n', $out->NodeName);
        self::assertSame('d', $out->Datacenter);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->NodeName = 'node-2';
        $d->Datacenter = 'dc2';
        $n = ACLNodeIdentity::jsonUnserialize($d);
        self::assertSame('node-2', $n->getNodeName());
        self::assertSame('dc2', $n->getDatacenter());
    }
}

