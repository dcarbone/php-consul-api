<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new ACLEntry();
        self::assertSame(0, $e->getCreateIndex());
        self::assertSame(0, $e->getModifyIndex());
        self::assertSame('', $e->getID());
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getType());
        self::assertSame('', $e->getRules());
    }

    public function testConstructorWithParams(): void
    {
        $e = new ACLEntry(CreateIndex: 1, ModifyIndex: 2, ID: 'id-1', Name: 'test', Type: 'client', Rules: 'key "" { policy = "read" }');
        self::assertSame(1, $e->getCreateIndex());
        self::assertSame(2, $e->getModifyIndex());
        self::assertSame('id-1', $e->getID());
        self::assertSame('test', $e->getName());
        self::assertSame('client', $e->getType());
        self::assertSame('key "" { policy = "read" }', $e->getRules());
    }

    public function testFluentSetters(): void
    {
        $e = new ACLEntry();
        $result = $e->setCreateIndex(10)->setModifyIndex(20)->setID('abc')->setName('n')->setType('management')->setRules('r');
        self::assertSame($e, $result);
        self::assertSame(10, $e->getCreateIndex());
        self::assertSame(20, $e->getModifyIndex());
        self::assertSame('abc', $e->getID());
        self::assertSame('n', $e->getName());
        self::assertSame('management', $e->getType());
        self::assertSame('r', $e->getRules());
    }

    public function testJsonSerialize(): void
    {
        $e = new ACLEntry(ID: 'x', Name: 'y', Type: 'client', Rules: 'r');
        $out = $e->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertSame('y', $out->Name);
        self::assertSame('client', $out->Type);
        self::assertSame('r', $out->Rules);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->CreateIndex = 5;
        $d->ModifyIndex = 10;
        $d->ID = 'id-2';
        $d->Name = 'entry';
        $d->Type = 'management';
        $d->Rules = 'rules';
        $e = ACLEntry::jsonUnserialize($d);
        self::assertSame(5, $e->getCreateIndex());
        self::assertSame(10, $e->getModifyIndex());
        self::assertSame('id-2', $e->getID());
        self::assertSame('entry', $e->getName());
        self::assertSame('management', $e->getType());
        self::assertSame('rules', $e->getRules());
    }
}

