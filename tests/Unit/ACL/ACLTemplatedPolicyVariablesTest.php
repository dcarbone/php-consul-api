<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicyVariables;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLTemplatedPolicyVariablesTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $v = new ACLTemplatedPolicyVariables();
        self::assertSame('', $v->getName());
    }

    public function testConstructorWithParams(): void
    {
        $v = new ACLTemplatedPolicyVariables(Name: 'my-svc');
        self::assertSame('my-svc', $v->getName());
    }

    public function testFluentSetters(): void
    {
        $v = new ACLTemplatedPolicyVariables();
        $result = $v->setName('test');
        self::assertSame($v, $result);
        self::assertSame('test', $v->getName());
    }

    public function testJsonSerialize(): void
    {
        $v = new ACLTemplatedPolicyVariables(Name: 'n');
        $out = $v->jsonSerialize();
        self::assertSame('n', $out->Name);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Name = 'test';
        $v = ACLTemplatedPolicyVariables::jsonUnserialize($d);
        self::assertSame('test', $v->getName());
    }
}

