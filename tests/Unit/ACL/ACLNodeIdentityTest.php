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
        self::assertSame('', $n->getDatacenter());
    }

    public function testConstructorWithParams(): void
    {
        $n = new ACLNodeIdentity(NodeName: 'node-1', Datacenter: 'dc1');
        self::assertSame('node-1', $n->getNodeName());
        self::assertSame('dc1', $n->getDatacenter());
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

