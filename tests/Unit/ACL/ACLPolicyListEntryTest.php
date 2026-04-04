<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLPolicyListEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLPolicyListEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new ACLPolicyListEntry();
        self::assertSame('', $p->getID());
        self::assertSame('', $p->getName());
        self::assertSame('', $p->getDescription());
        self::assertSame([], $p->getDatacenters());
        self::assertSame('', $p->getHash());
        self::assertSame(0, $p->getCreateIndex());
        self::assertSame(0, $p->getModifyIndex());
        self::assertSame('', $p->getNamespace());
        self::assertSame('', $p->getPartition());
    }

    public function testConstructorWithParams(): void
    {
        $p = new ACLPolicyListEntry(ID: 'id', Name: 'n', Datacenters: ['dc1']);
        self::assertSame('id', $p->getID());
        self::assertSame('n', $p->getName());
        self::assertSame(['dc1'], $p->getDatacenters());
    }

    public function testJsonSerialize(): void
    {
        $p = new ACLPolicyListEntry(ID: 'x', Name: 'y');
        $out = $p->jsonSerialize();
        self::assertSame('x', $out->ID);
        self::assertSame('y', $out->Name);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->ID = 'a';
        $d->Name = 'b';
        $d->Description = 'c';
        $d->Datacenters = ['dc1'];
        $d->Hash = 'h';
        $d->CreateIndex = 1;
        $d->ModifyIndex = 2;
        $p = ACLPolicyListEntry::jsonUnserialize($d);
        self::assertSame('a', $p->getID());
        self::assertSame(['dc1'], $p->getDatacenters());
    }
}

