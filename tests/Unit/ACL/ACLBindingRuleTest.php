<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLBindingRule;
use DCarbone\PHPConsulAPI\ACL\BindingRuleBindType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLBindingRuleTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $b = new ACLBindingRule();
        self::assertSame('', $b->getID());
        self::assertSame('', $b->getDescription());
        self::assertSame('', $b->getAuthMethod());
        self::assertSame('', $b->getSelector());
        self::assertSame(BindingRuleBindType::UNDEFINED, $b->getBindType());
        self::assertSame('', $b->getBindType()->value);
        self::assertSame('', $b->getBindName());
        self::assertSame(0, $b->getCreateIndex());
        self::assertSame(0, $b->getModifyIndex());
        self::assertSame('', $b->getNamespace());
    }

    public function testConstructorWithParams(): void
    {
        $b = new ACLBindingRule(
            ID: 'br-1',
            Description: 'desc',
            AuthMethod: 'method',
            Selector: 'serviceaccount.name==web',
            BindType: BindingRuleBindType::Service,
            BindName: 'web',
            CreateIndex: 1,
            ModifyIndex: 2,
            Namespace: 'ns',
        );
        self::assertSame('br-1', $b->getID());
        self::assertSame(BindingRuleBindType::Service, $b->getBindType());
        self::assertSame('web', $b->getBindName());
    }

    public function testFluentSetters(): void
    {
        $b = new ACLBindingRule();
        $result = $b->setID('a')->setDescription('d')->setAuthMethod('m')
            ->setSelector('s')->setBindType(BindingRuleBindType::Role)
            ->setBindName('r')->setCreateIndex(1)->setModifyIndex(2)->setNamespace('ns');
        self::assertSame($b, $result);
        self::assertSame(BindingRuleBindType::Role, $b->getBindType());
    }

    public function testJsonSerialize(): void
    {
        $b = new ACLBindingRule(ID: 'x', BindType: BindingRuleBindType::Service, BindName: 'web');
        $out = $b->jsonSerialize();
        self::assertSame('x', $out->ID);
        self::assertSame(BindingRuleBindType::Service, $out->BindType);
        self::assertSame('web', $out->BindName);
    }

    public function testJsonSerializeOmitsEmptyNamespace(): void
    {
        $b = new ACLBindingRule(ID: 'x');
        $out = $b->jsonSerialize();
        self::assertObjectNotHasProperty('Namespace', $out);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->ID = 'id-1';
        $d->Description = 'desc';
        $d->AuthMethod = 'auth';
        $d->Selector = 'sel';
        $d->BindType = BindingRuleBindType::Service;
        $d->BindName = 'web';
        $d->CreateIndex = 5;
        $d->ModifyIndex = 10;
        $b = ACLBindingRule::jsonUnserialize($d);
        self::assertSame('id-1', $b->getID());
        self::assertSame('web', $b->getBindName());
        self::assertSame(BindingRuleBindType::Service, $b->getBindType());
    }

    public function testConstructorWithStringBindType(): void
    {
        $b = new ACLBindingRule(
            ID: 'br-str',
            BindType: 'service',
            BindName: 'web',
        );
        self::assertSame(BindingRuleBindType::Service, $b->getBindType());
        self::assertSame(BindingRuleBindType::Service, $b->BindType);
        self::assertSame('web', $b->getBindName());
    }

    public function testSetBindTypeWithString(): void
    {
        $b = new ACLBindingRule();
        $result = $b->setBindType('role');
        self::assertSame($b, $result);
        self::assertSame(BindingRuleBindType::Role, $b->getBindType());
        self::assertSame(BindingRuleBindType::Role, $b->BindType);
    }

    public function testSetBindTypeWithEnum(): void
    {
        $b = new ACLBindingRule();
        $result = $b->setBindType(BindingRuleBindType::Node);
        self::assertSame($b, $result);
        self::assertSame(BindingRuleBindType::Node, $b->getBindType());
        self::assertSame(BindingRuleBindType::Node, $b->BindType);
    }
}

