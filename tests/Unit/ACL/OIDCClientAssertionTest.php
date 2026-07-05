<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\OIDCClientAssertion;
use DCarbone\PHPConsulAPI\ACL\OIDCClientAssertionKey;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class OIDCClientAssertionTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $a = new OIDCClientAssertion();
        self::assertSame([], $a->getAudience());
        self::assertNull($a->getPrivateKey());
        self::assertSame('', $a->getKeyAlgorithm());
    }

    public function testConstructorWithParams(): void
    {
        $key = new OIDCClientAssertionKey(PemKey: 'pem');
        $a = new OIDCClientAssertion(
            Audience: ['aud1', 'aud2'],
            PrivateKey: $key,
            KeyAlgorithm: 'RS256',
        );
        self::assertSame(['aud1', 'aud2'], $a->getAudience());
        self::assertSame($key, $a->getPrivateKey());
        self::assertSame('RS256', $a->getKeyAlgorithm());
    }

    public function testFluentSetters(): void
    {
        $a = new OIDCClientAssertion();
        $key = new OIDCClientAssertionKey(PemKey: 'k');
        $result = $a->setAudience('a1')->setPrivateKey($key)->setKeyAlgorithm('ES256');
        self::assertSame($a, $result);
        self::assertSame(['a1'], $a->getAudience());
        self::assertSame($key, $a->getPrivateKey());
        self::assertSame('ES256', $a->getKeyAlgorithm());
    }

    public function testJsonSerialize(): void
    {
        $key = new OIDCClientAssertionKey(PemKey: 'pem');
        $a = new OIDCClientAssertion(Audience: ['x'], PrivateKey: $key, KeyAlgorithm: 'RS256');
        $out = $a->jsonSerialize();
        self::assertSame(['x'], $out->Audience);
        self::assertSame('pem', $out->PrivateKey->PemKey);
        self::assertSame('RS256', $out->KeyAlgorithm);
    }

    public function testJsonSerializeOmitsEmptyKeyAlgorithm(): void
    {
        $a = new OIDCClientAssertion(Audience: ['x']);
        $out = $a->jsonSerialize();
        self::assertObjectNotHasProperty('KeyAlgorithm', $out);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Audience = ['a', 'b'];
        $pk = new \stdClass();
        $pk->PemKey = 'test-pem';
        $d->PrivateKey = $pk;
        $d->KeyAlgorithm = 'RS384';
        $a = OIDCClientAssertion::jsonUnserialize($d);
        self::assertSame(['a', 'b'], $a->getAudience());
        self::assertNotNull($a->getPrivateKey());
        self::assertSame('test-pem', $a->getPrivateKey()->getPemKey());
        self::assertSame('RS384', $a->getKeyAlgorithm());
    }

    public function testJsonUnserializeNullPrivateKey(): void
    {
        $d = new \stdClass();
        $d->Audience = ['a'];
        $d->PrivateKey = null;
        $d->KeyAlgorithm = '';
        $a = OIDCClientAssertion::jsonUnserialize($d);
        self::assertNull($a->getPrivateKey());
    }
}

