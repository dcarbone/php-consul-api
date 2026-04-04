<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLAuthMethodNamespaceRuleTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new ACLAuthMethodNamespaceRule();
        self::assertSame('', $r->getSelector());
        self::assertSame('', $r->getBindNamespace());
    }

    public function testConstructorWithParams(): void
    {
        $r = new ACLAuthMethodNamespaceRule(Selector: 'sel', BindNamespace: 'ns');
        self::assertSame('sel', $r->getSelector());
        self::assertSame('ns', $r->getBindNamespace());
    }

    public function testFluentSetters(): void
    {
        $r = new ACLAuthMethodNamespaceRule();
        $result = $r->setSelector('s')->setBindNamespace('b');
        self::assertSame($r, $result);
        self::assertSame('s', $r->getSelector());
        self::assertSame('b', $r->getBindNamespace());
    }

    public function testJsonSerializeOmitsEmptyFields(): void
    {
        $r = new ACLAuthMethodNamespaceRule();
        $out = $r->jsonSerialize();
        self::assertObjectNotHasProperty('Selector', $out);
        self::assertObjectNotHasProperty('BindNamespace', $out);
    }

    public function testJsonSerializeIncludesNonEmptyFields(): void
    {
        $r = new ACLAuthMethodNamespaceRule(Selector: 's', BindNamespace: 'b');
        $out = $r->jsonSerialize();
        self::assertSame('s', $out->Selector);
        self::assertSame('b', $out->BindNamespace);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Selector = 'sel';
        $d->BindNamespace = 'ns';
        $r = ACLAuthMethodNamespaceRule::jsonUnserialize($d);
        self::assertSame('sel', $r->getSelector());
        self::assertSame('ns', $r->getBindNamespace());
    }
}

