<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy;
use DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicyVariables;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLTemplatedPolicyTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $t = new ACLTemplatedPolicy();
        self::assertSame('', $t->getTemplateName());
        self::assertNull($t->getTemplateVariables());
        self::assertSame([], $t->getDatacenters());
    }

    public function testConstructorWithParams(): void
    {
        $vars = new ACLTemplatedPolicyVariables(Name: 'my-svc');
        $t = new ACLTemplatedPolicy(TemplateName: 'builtin/service', TemplateVariables: $vars, Datacenters: ['dc1']);
        self::assertSame('builtin/service', $t->getTemplateName());
        self::assertSame($vars, $t->getTemplateVariables());
        self::assertSame(['dc1'], $t->getDatacenters());
    }

    public function testFluentSetters(): void
    {
        $t = new ACLTemplatedPolicy();
        $vars = new ACLTemplatedPolicyVariables(Name: 'v');
        $result = $t->setTemplateName('t')->setTemplateVariables($vars)->setDatacenters('dc1', 'dc2');
        self::assertSame($t, $result);
        self::assertSame('t', $t->getTemplateName());
        self::assertSame($vars, $t->getTemplateVariables());
        self::assertSame(['dc1', 'dc2'], $t->getDatacenters());
    }

    public function testJsonSerializeOmitsNullAndEmpty(): void
    {
        $t = new ACLTemplatedPolicy(TemplateName: 'tmpl');
        $out = $t->jsonSerialize();
        self::assertSame('tmpl', $out->TemplateName);
        self::assertObjectNotHasProperty('TemplateVariables', $out);
        self::assertObjectNotHasProperty('Datacenters', $out);
    }

    public function testJsonSerializeIncludesValues(): void
    {
        $vars = new ACLTemplatedPolicyVariables(Name: 'n');
        $t = new ACLTemplatedPolicy(TemplateName: 'tmpl', TemplateVariables: $vars, Datacenters: ['dc1']);
        $out = $t->jsonSerialize();
        self::assertSame('tmpl', $out->TemplateName);
        self::assertSame($vars, $out->TemplateVariables);
        self::assertSame(['dc1'], $out->Datacenters);
    }
}

