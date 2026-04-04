<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLOIDCCallbackParams;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLOIDCCallbackParamsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new ACLOIDCCallbackParams();
        self::assertSame('', $p->getAuthMethod());
        self::assertSame('', $p->getState());
        self::assertSame('', $p->getCode());
        self::assertSame('', $p->getClientNonce());
    }

    public function testConstructorWithParams(): void
    {
        $p = new ACLOIDCCallbackParams(AuthMethod: 'oidc', State: 's', Code: 'c', ClientNonce: 'n');
        self::assertSame('oidc', $p->getAuthMethod());
        self::assertSame('s', $p->getState());
        self::assertSame('c', $p->getCode());
        self::assertSame('n', $p->getClientNonce());
    }

    public function testFluentSetters(): void
    {
        $p = new ACLOIDCCallbackParams();
        $result = $p->setAuthMethod('m')->setState('st')->setCode('cd')->setClientNonce('nc');
        self::assertSame($p, $result);
        self::assertSame('m', $p->getAuthMethod());
    }

    public function testJsonSerialize(): void
    {
        $p = new ACLOIDCCallbackParams(AuthMethod: 'a', State: 's', Code: 'c', ClientNonce: 'n');
        $out = $p->jsonSerialize();
        self::assertSame('a', $out->AuthMethod);
        self::assertSame('s', $out->State);
        self::assertSame('c', $out->Code);
        self::assertSame('n', $out->ClientNonce);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->AuthMethod = 'oidc';
        $d->State = 'state';
        $d->Code = 'code';
        $d->ClientNonce = 'nonce';
        $p = ACLOIDCCallbackParams::jsonUnserialize($d);
        self::assertSame('oidc', $p->getAuthMethod());
        self::assertSame('state', $p->getState());
    }
}

