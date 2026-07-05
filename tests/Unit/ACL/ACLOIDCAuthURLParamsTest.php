<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLOIDCAuthURLParams;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLOIDCAuthURLParamsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new ACLOIDCAuthURLParams();
        self::assertSame('', $p->getAuthMethod());
        self::assertSame('', $p->getRedirectURI());
        self::assertSame('', $p->getClientNonce());
    }

    public function testConstructorWithParams(): void
    {
        $p = new ACLOIDCAuthURLParams(AuthMethod: 'oidc', RedirectURI: 'https://example.com/callback', ClientNonce: 'nonce', Meta: ['k' => 'v']);
        self::assertSame('oidc', $p->getAuthMethod());
        self::assertSame('https://example.com/callback', $p->getRedirectURI());
        self::assertSame('nonce', $p->getClientNonce());
    }

    public function testFluentSetters(): void
    {
        $p = new ACLOIDCAuthURLParams();
        $result = $p->setAuthMethod('m')->setRedirectURI('u')->setClientNonce('n');
        self::assertSame($p, $result);
    }

    public function testJsonSerialize(): void
    {
        $p = new ACLOIDCAuthURLParams(AuthMethod: 'oidc', RedirectURI: 'uri', ClientNonce: 'nonce');
        $out = $p->jsonSerialize();
        self::assertSame('oidc', $out->AuthMethod);
        self::assertSame('uri', $out->RedirectURI);
        self::assertSame('nonce', $out->ClientNonce);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->AuthMethod = 'oidc';
        $d->RedirectURI = 'https://example.com';
        $d->ClientNonce = 'nonce';
        $p = ACLOIDCAuthURLParams::jsonUnserialize($d);
        self::assertSame('oidc', $p->getAuthMethod());
        self::assertSame('https://example.com', $p->getRedirectURI());
    }
}

