<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLLoginParams;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLLoginParamsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new ACLLoginParams();
        self::assertSame('', $p->getAuthMethod());
        self::assertSame('', $p->getBearerToken());
    }

    public function testConstructorWithParams(): void
    {
        $p = new ACLLoginParams(AuthMethod: 'k8s', BearerToken: 'token-abc');
        self::assertSame('k8s', $p->getAuthMethod());
        self::assertSame('token-abc', $p->getBearerToken());
    }

    public function testSetMetaDirectly(): void
    {
        $p = new ACLLoginParams();
        // Meta uses null semantics via MetaField trait.
        // After setMeta, accessing $p->Meta directly returns data through __get.
        $p->setMeta(['key' => 'val']);
        self::assertSame('val', $p->Meta['key']);
    }

    public function testSetMetaKey(): void
    {
        $p = new ACLLoginParams();
        $p->setMetaKey('key', 'val');
        self::assertSame('val', $p->Meta['key']);
    }

    public function testConstructorDefaultMeta(): void
    {
        $p = new ACLLoginParams();
        self::assertNull($p->getMeta());
    }

    public function testFluentSetters(): void
    {
        $p = new ACLLoginParams();
        $result = $p->setAuthMethod('m')->setBearerToken('t');
        self::assertSame($p, $result);
        self::assertSame('m', $p->getAuthMethod());
        self::assertSame('t', $p->getBearerToken());
    }

    public function testJsonSerialize(): void
    {
        $p = new ACLLoginParams(AuthMethod: 'auth', BearerToken: 'tok');
        $out = $p->jsonSerialize();
        self::assertSame('auth', $out->AuthMethod);
        self::assertSame('tok', $out->BearerToken);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->AuthMethod = 'auth';
        $d->BearerToken = 'tok';
        $d->Meta = new \stdClass();
        $d->Meta->key = 'val';
        $p = ACLLoginParams::jsonUnserialize($d);
        self::assertSame('auth', $p->getAuthMethod());
        self::assertSame('tok', $p->getBearerToken());
    }
}

