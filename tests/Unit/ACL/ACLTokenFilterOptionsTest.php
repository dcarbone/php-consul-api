<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLTokenFilterOptions;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLTokenFilterOptionsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $o = new ACLTokenFilterOptions();
        self::assertSame('', $o->getAuthMethod());
        self::assertSame('', $o->getPolicy());
        self::assertSame('', $o->getRole());
        self::assertSame('', $o->getServiceName());
    }

    public function testConstructorWithParams(): void
    {
        $o = new ACLTokenFilterOptions(
            AuthMethod: 'kubernetes',
            Policy: 'pol-1',
            Role: 'role-1',
            ServiceName: 'web',
        );
        self::assertSame('kubernetes', $o->getAuthMethod());
        self::assertSame('kubernetes', $o->AuthMethod);
        self::assertSame('pol-1', $o->getPolicy());
        self::assertSame('role-1', $o->getRole());
        self::assertSame('web', $o->getServiceName());
    }

    public function testFluentSetters(): void
    {
        $o = new ACLTokenFilterOptions();
        $result = $o->setAuthMethod('a')->setPolicy('p')->setRole('r')->setServiceName('s');
        self::assertSame($o, $result);
        self::assertSame('a', $o->getAuthMethod());
        self::assertSame('p', $o->getPolicy());
        self::assertSame('r', $o->getRole());
        self::assertSame('s', $o->getServiceName());
    }

    public function testJsonSerializeOmitsEmpty(): void
    {
        $o = new ACLTokenFilterOptions();
        $out = $o->jsonSerialize();
        self::assertObjectNotHasProperty('AuthMethod', $out);
        self::assertObjectNotHasProperty('Policy', $out);
        self::assertObjectNotHasProperty('Role', $out);
        self::assertObjectNotHasProperty('ServiceName', $out);
    }

    public function testJsonSerializeIncludesSet(): void
    {
        $o = new ACLTokenFilterOptions(AuthMethod: 'jwt', ServiceName: 'api');
        $out = $o->jsonSerialize();
        self::assertSame('jwt', $out->AuthMethod);
        self::assertSame('api', $out->ServiceName);
        self::assertObjectNotHasProperty('Policy', $out);
        self::assertObjectNotHasProperty('Role', $out);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->AuthMethod = 'oidc';
        $d->Policy = 'p';
        $d->Role = 'r';
        $d->ServiceName = 'sn';
        $o = ACLTokenFilterOptions::jsonUnserialize($d);
        self::assertSame('oidc', $o->getAuthMethod());
        self::assertSame('p', $o->getPolicy());
        self::assertSame('r', $o->getRole());
        self::assertSame('sn', $o->getServiceName());
    }
}

