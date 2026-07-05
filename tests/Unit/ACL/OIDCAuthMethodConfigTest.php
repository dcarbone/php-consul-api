<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\OIDCAuthMethodConfig;
use DCarbone\PHPConsulAPI\ACL\OIDCClientAssertion;
use DCarbone\PHPConsulAPI\ACL\OIDCClientAssertionKey;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class OIDCAuthMethodConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new OIDCAuthMethodConfig();
        self::assertSame([], $c->getJWTSupportedAlgs());
        self::assertSame([], $c->getBoundAudiences());
        self::assertSame([], $c->getClaimMappings());
        self::assertSame([], $c->getListClaimMappings());
        self::assertSame('', $c->getOIDCDiscoveryURL());
        self::assertSame('', $c->getOIDCDiscoveryCACert());
        self::assertSame('', $c->getOIDCClientID());
        self::assertSame('', $c->getOIDCClientSecret());
        self::assertNull($c->getOIDCClientAssertion());
        self::assertNull($c->getOIDCClientUsePKCE());
        self::assertSame([], $c->getOIDCScopes());
        self::assertSame([], $c->getOIDCACRValues());
        self::assertSame([], $c->getAllowedRedirectURIs());
        self::assertFalse($c->isVerboseOIDCLogging());
        self::assertSame('', $c->getJWKSURL());
        self::assertSame('', $c->getJWKSCACert());
        self::assertSame([], $c->getJWTValidationPubKeys());
        self::assertSame('', $c->getBoundIssuer());
        self::assertSame(0, $c->getExpirationLeeway()->Nanoseconds());
        self::assertSame(0, $c->getNotBeforeLeeway()->Nanoseconds());
        self::assertSame(0, $c->getClockSkewLeeway()->Nanoseconds());
    }

    public function testConstructorWithParams(): void
    {
        $assertion = new OIDCClientAssertion(
            Audience: ['https://oidc.example.com'],
            PrivateKey: new OIDCClientAssertionKey(PemKey: 'test-pem-key'),
            KeyAlgorithm: 'RS256',
        );
        $c = new OIDCAuthMethodConfig(
            OIDCDiscoveryURL: 'https://oidc.example.com',
            OIDCClientID: 'client-id',
            OIDCClientSecret: 'client-secret',
            OIDCClientAssertion: $assertion,
            OIDCClientUsePKCE: true,
            AllowedRedirectURIs: ['https://example.com/callback'],
            BoundAudiences: ['aud1'],
            ClaimMappings: ['sub' => 'name'],
        );
        self::assertSame('https://oidc.example.com', $c->getOIDCDiscoveryURL());
        self::assertSame('client-id', $c->getOIDCClientID());
        self::assertSame(['https://example.com/callback'], $c->getAllowedRedirectURIs());
        self::assertSame(['aud1'], $c->getBoundAudiences());
        self::assertSame(['sub' => 'name'], $c->getClaimMappings());
        self::assertSame($assertion, $c->getOIDCClientAssertion());
        self::assertTrue($c->getOIDCClientUsePKCE());
    }

    public function testFluentSetters(): void
    {
        $c = new OIDCAuthMethodConfig();
        $result = $c->setJWTSupportedAlgs('RS256')
            ->setBoundAudiences('aud')
            ->setClaimMappings(['k' => 'v'])
            ->setListClaimMappings(['k' => 'v'])
            ->setOIDCDiscoveryURL('url')
            ->setOIDCDiscoveryCACert('cert')
            ->setOIDCClientID('id')
            ->setOIDCClientSecret('secret')
            ->setOIDCScopes('openid')
            ->setOIDCACRValues('acr')
            ->setAllowedRedirectURIs('uri')
            ->setVerboseOIDCLogging(true)
            ->setJWKSURL('jwks')
            ->setJWKSCACert('jcert')
            ->setJWTValidationPubKeys('key')
            ->setBoundIssuer('issuer')
            ->setExpirationLeeway('5m')
            ->setNotBeforeLeeway('10m')
            ->setClockSkewLeeway('1m');
        self::assertSame($c, $result);
        self::assertSame(['RS256'], $c->getJWTSupportedAlgs());
        self::assertTrue($c->isVerboseOIDCLogging());
    }

    public function testOIDCClientAssertionSetters(): void
    {
        $c = new OIDCAuthMethodConfig();

        $assertion = new OIDCClientAssertion(
            Audience: ['https://example.com'],
            PrivateKey: new OIDCClientAssertionKey(PemKey: 'pem-data'),
            KeyAlgorithm: 'RS256',
        );
        $c->setOIDCClientAssertion($assertion);
        self::assertSame($assertion, $c->getOIDCClientAssertion());

        $c->setOIDCClientAssertion(null);
        self::assertNull($c->getOIDCClientAssertion());

        $c->setOIDCClientUsePKCE(true);
        self::assertTrue($c->getOIDCClientUsePKCE());

        $c->setOIDCClientUsePKCE(false);
        self::assertFalse($c->getOIDCClientUsePKCE());

        $c->setOIDCClientUsePKCE(null);
        self::assertNull($c->getOIDCClientUsePKCE());
    }

    public function testSetClaimMapping(): void
    {
        $c = new OIDCAuthMethodConfig();
        $c->setClaimMapping('sub', 'name');
        self::assertSame(['sub' => 'name'], $c->getClaimMappings());
    }

    public function testJsonSerializeOmitsEmptyFields(): void
    {
        $c = new OIDCAuthMethodConfig();
        $out = $c->jsonSerialize();
        self::assertObjectNotHasProperty('JWTSupportedAlgs', $out);
        self::assertObjectNotHasProperty('OIDCDiscoveryURL', $out);
        self::assertObjectNotHasProperty('VerboseOIDCLogging', $out);
        self::assertObjectNotHasProperty('OIDCClientAssertion', $out);
        self::assertObjectNotHasProperty('OIDCClientUsePKCE', $out);
    }

    public function testJsonSerializeWithAssertionAndPKCE(): void
    {
        $assertion = new OIDCClientAssertion(
            Audience: ['https://example.com'],
            KeyAlgorithm: 'RS256',
        );
        $c = new OIDCAuthMethodConfig(
            OIDCClientAssertion: $assertion,
            OIDCClientUsePKCE: true,
        );
        $out = $c->jsonSerialize();
        self::assertObjectHasProperty('OIDCClientAssertion', $out);
        self::assertObjectHasProperty('OIDCClientUsePKCE', $out);
        self::assertTrue($out->OIDCClientUsePKCE);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->OIDCDiscoveryURL = 'https://oidc.example.com';
        $d->OIDCClientID = 'cid';
        $d->OIDCClientSecret = 'csec';
        $d->BoundAudiences = ['aud'];
        $d->AllowedRedirectURIs = ['uri'];
        $d->ClaimMappings = new \stdClass();
        $d->ClaimMappings->sub = 'name';
        $d->ListClaimMappings = new \stdClass();
        $d->JWTSupportedAlgs = ['RS256'];
        $d->OIDCScopes = ['openid'];
        $d->OIDCACRValues = [];
        $d->JWTValidationPubKeys = [];
        $d->ExpirationLeeway = '5m0s';
        $d->NotBeforeLeeway = '0s';
        $d->ClockSkewLeeway = '0s';
        $d->OIDCClientUsePKCE = true;
        $c = OIDCAuthMethodConfig::jsonUnserialize($d);
        self::assertSame('https://oidc.example.com', $c->getOIDCDiscoveryURL());
        self::assertSame(['aud'], $c->getBoundAudiences());
        self::assertSame(['sub' => 'name'], $c->getClaimMappings());
        self::assertTrue($c->getOIDCClientUsePKCE());
    }

    public function testJsonUnserializeWithAssertion(): void
    {
        $d = new \stdClass();
        $d->OIDCDiscoveryURL = 'https://oidc.example.com';
        $d->OIDCClientID = 'cid';
        $d->BoundAudiences = [];
        $d->AllowedRedirectURIs = [];
        $d->ClaimMappings = new \stdClass();
        $d->ListClaimMappings = new \stdClass();
        $d->JWTSupportedAlgs = [];
        $d->OIDCScopes = [];
        $d->OIDCACRValues = [];
        $d->JWTValidationPubKeys = [];
        $d->ExpirationLeeway = '0s';
        $d->NotBeforeLeeway = '0s';
        $d->ClockSkewLeeway = '0s';

        $assertionObj = new \stdClass();
        $assertionObj->Audience = ['https://example.com'];
        $assertionObj->KeyAlgorithm = 'RS256';
        $keyObj = new \stdClass();
        $keyObj->PemKey = 'test-pem';
        $assertionObj->PrivateKey = $keyObj;
        $d->OIDCClientAssertion = $assertionObj;

        $c = OIDCAuthMethodConfig::jsonUnserialize($d);
        self::assertNotNull($c->getOIDCClientAssertion());
        self::assertSame(['https://example.com'], $c->getOIDCClientAssertion()->getAudience());
        self::assertSame('RS256', $c->getOIDCClientAssertion()->getKeyAlgorithm());
        self::assertSame('test-pem', $c->getOIDCClientAssertion()->getPrivateKey()->getPemKey());
    }
}

