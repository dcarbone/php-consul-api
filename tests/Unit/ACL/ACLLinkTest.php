<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLLink;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLLinkTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $l = new ACLLink();
        self::assertSame('', $l->getID());
        self::assertSame('', $l->getName());
    }

    public function testConstructorWithParams(): void
    {
        $l = new ACLLink(ID: 'link-id', Name: 'link-name');
        self::assertSame('link-id', $l->getID());
        self::assertSame('link-name', $l->getName());
    }

    public function testFluentSetters(): void
    {
        $l = new ACLLink();
        $result = $l->setID('a')->setName('b');
        self::assertSame($l, $result);
        self::assertSame('a', $l->getID());
        self::assertSame('b', $l->getName());
    }

    public function testJsonSerialize(): void
    {
        $l = new ACLLink(ID: 'x', Name: 'y');
        $out = $l->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertSame('y', $out->Name);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->ID = 'id-1';
        $d->Name = 'name-1';
        $l = ACLLink::jsonUnserialize($d);
        self::assertSame('id-1', $l->getID());
        self::assertSame('name-1', $l->getName());
    }
}

