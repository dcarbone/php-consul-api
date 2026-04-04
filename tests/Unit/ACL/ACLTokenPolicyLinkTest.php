<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLTokenPolicyLinkTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $l = new ACLTokenPolicyLink();
        self::assertSame('', $l->getID());
        self::assertSame('', $l->ID);
        self::assertSame('', $l->getName());
        self::assertSame('', $l->Name);
    }

    public function testConstructorWithParams(): void
    {
        $l = new ACLTokenPolicyLink(ID: 'tpl-id', Name: 'tpl-name');
        self::assertSame('tpl-id', $l->getID());
        self::assertSame('tpl-id', $l->ID);
        self::assertSame('tpl-name', $l->getName());
        self::assertSame('tpl-name', $l->Name);
    }

    public function testFluentSetters(): void
    {
        $l = new ACLTokenPolicyLink();
        $result = $l->setID('a')->setName('b');
        self::assertSame($l, $result);
        self::assertSame('a', $l->getID());
        self::assertSame('a', $l->ID);
        self::assertSame('b', $l->getName());
        self::assertSame('b', $l->Name);
    }

    public function testJsonSerialize(): void
    {
        $l = new ACLTokenPolicyLink(ID: 'x', Name: 'y');
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
        $l = ACLTokenPolicyLink::jsonUnserialize($d);
        self::assertInstanceOf(ACLTokenPolicyLink::class, $l);
        self::assertSame('id-1', $l->getID());
        self::assertSame('id-1', $l->ID);
        self::assertSame('name-1', $l->getName());
        self::assertSame('name-1', $l->Name);
    }
}

