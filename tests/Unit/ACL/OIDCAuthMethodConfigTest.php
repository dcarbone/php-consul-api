<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\OIDCAuthMethodConfig;
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
        $c = new OIDCAuthMethodConfig(
            OIDCDiscoveryURL: 'https://oidc.example.com',
            OIDCClientID: 'client-id',
            OIDCClientSecret: 'client-secret',
            AllowedRedirectURIs: ['https://example.com/callback'],
            BoundAudiences: ['aud1'],
            ClaimMappings: ['sub' => 'name'],
        );
        self::assertSame('https://oidc.example.com', $c->getOIDCDiscoveryURL());
        self::assertSame('client-id', $c->getOIDCClientID());
        self::assertSame(['https://example.com/callback'], $c->getAllowedRedirectURIs());
        self::assertSame(['aud1'], $c->getBoundAudiences());
        self::assertSame(['sub' => 'name'], $c->getClaimMappings());
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
        $c = OIDCAuthMethodConfig::jsonUnserialize($d);
        self::assertSame('https://oidc.example.com', $c->getOIDCDiscoveryURL());
        self::assertSame(['aud'], $c->getBoundAudiences());
        self::assertSame(['sub' => 'name'], $c->getClaimMappings());
    }
}

