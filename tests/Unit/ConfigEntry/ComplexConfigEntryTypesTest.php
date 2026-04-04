<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\LinkedService;
use DCarbone\PHPConsulAPI\ConfigEntry\LoadBalancer;
use DCarbone\PHPConsulAPI\ConfigEntry\RingHashConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\LeastRequestConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfiguration;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionPermission;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionHTTPPermission;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionHTTPHeaderPermission;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTRequirement;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTClaimVerification;
use DCarbone\PHPConsulAPI\ConfigEntry\SourceIntention;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverSubset;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverRedirect;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverPolicy;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverTarget;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverPrioritizeByLocality;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ComplexConfigEntryTypesTest extends TestCase
{
    // --- LinkedService ---

    public function testLinkedServiceDefaults(): void
    {
        $l = new LinkedService();
        self::assertSame('', $l->getNamespace());
        self::assertSame('', $l->getName());
        self::assertSame('', $l->getCAFile());
        self::assertSame('', $l->getCertFile());
        self::assertSame('', $l->getKeyFile());
        self::assertSame('', $l->getSNI());
    }

    public function testLinkedServiceWithParams(): void
    {
        $l = new LinkedService(Namespace: 'ns', Name: 'web', CAFile: 'ca.pem', CertFile: 'cert.pem', KeyFile: 'key.pem', SNI: 'web.consul');
        self::assertSame('ns', $l->getNamespace());
        self::assertSame('web', $l->getName());
        self::assertSame('ca.pem', $l->getCAFile());
    }

    // --- LoadBalancer ---

    public function testLoadBalancerDefaults(): void
    {
        $l = new LoadBalancer();
        self::assertSame('', $l->getPolicy());
        self::assertNull($l->getRingHashConfig());
        self::assertNull($l->getLeastRequestConfig());
    }

    public function testLoadBalancerWithParams(): void
    {
        $rh = new RingHashConfig(MinimumRingSize: 1024);
        $l = new LoadBalancer(Policy: 'ring_hash', RingHashConfig: $rh);
        self::assertSame('ring_hash', $l->getPolicy());
        self::assertSame($rh, $l->getRingHashConfig());
    }

    // --- UpstreamConfig ---

    public function testUpstreamConfigDefaults(): void
    {
        $u = new UpstreamConfig();
        self::assertSame('', $u->getName());
        self::assertSame('', $u->getPartition());
        self::assertSame('', $u->getNamespace());
        self::assertSame('', $u->getProtocol());
        self::assertSame(0, $u->getConnectTimeoutMs());
    }

    public function testUpstreamConfigWithParams(): void
    {
        $u = new UpstreamConfig(Name: 'db', Protocol: 'tcp', ConnectTimeoutMs: 5000);
        self::assertSame('db', $u->getName());
        self::assertSame('tcp', $u->getProtocol());
        self::assertSame(5000, $u->getConnectTimeoutMs());
    }

    // --- UpstreamConfiguration ---

    public function testUpstreamConfigurationDefaults(): void
    {
        $uc = new UpstreamConfiguration();
        self::assertSame([], $uc->getOverrides());
        self::assertNull($uc->getDefaults());
    }

    public function testUpstreamConfigurationWithParams(): void
    {
        $defaults = new UpstreamConfig(Protocol: 'http');
        $uc = new UpstreamConfiguration(Defaults: $defaults);
        self::assertSame($defaults, $uc->getDefaults());
    }

    // --- IntentionHTTPHeaderPermission ---

    public function testIntentionHTTPHeaderPermissionDefaults(): void
    {
        $h = new IntentionHTTPHeaderPermission();
        self::assertSame('', $h->getName());
        self::assertFalse($h->isPresent());
        self::assertSame('', $h->getExact());
        self::assertSame('', $h->getPrefix());
        self::assertSame('', $h->getSuffix());
        self::assertSame('', $h->getRegex());
        self::assertFalse($h->isInvert());
    }

    public function testIntentionHTTPHeaderPermissionWithParams(): void
    {
        $h = new IntentionHTTPHeaderPermission(Name: 'x-service', Present: true, Exact: 'web');
        self::assertSame('x-service', $h->getName());
        self::assertTrue($h->isPresent());
        self::assertSame('web', $h->getExact());
    }

    // --- IntentionHTTPPermission ---

    public function testIntentionHTTPPermissionDefaults(): void
    {
        $p = new IntentionHTTPPermission();
        self::assertSame('', $p->getPathExact());
        self::assertSame('', $p->getPathPrefix());
        self::assertSame('', $p->getPathRegex());
        self::assertSame([], $p->getHeader());
        self::assertSame([], $p->getMethods());
    }

    public function testIntentionHTTPPermissionWithParams(): void
    {
        $h = new IntentionHTTPHeaderPermission(Name: 'x-header');
        $p = new IntentionHTTPPermission(PathPrefix: '/api', Header: [$h], Methods: ['GET', 'POST']);
        self::assertSame('/api', $p->getPathPrefix());
        self::assertCount(1, $p->getHeader());
        self::assertSame(['GET', 'POST'], $p->getMethods());
    }

    // --- IntentionJWTClaimVerification ---

    public function testIntentionJWTClaimVerificationDefaults(): void
    {
        $v = new IntentionJWTClaimVerification();
        self::assertSame([], $v->getPath());
        self::assertSame('', $v->getValue());
    }

    public function testIntentionJWTClaimVerificationWithParams(): void
    {
        $v = new IntentionJWTClaimVerification(Path: ['iss'], Value: 'my-issuer');
        self::assertSame(['iss'], $v->getPath());
        self::assertSame('my-issuer', $v->getValue());
    }

    // --- IntentionJWTRequirement ---

    public function testIntentionJWTRequirementDefaults(): void
    {
        $r = new IntentionJWTRequirement();
        self::assertSame('', $r->getName());
        self::assertSame([], $r->getVerifyClaims());
    }

    public function testIntentionJWTRequirementWithParams(): void
    {
        $claim = new IntentionJWTClaimVerification(Path: ['iss'], Value: 'val');
        $r = new IntentionJWTRequirement(Name: 'jwt-provider', VerifyClaims: [$claim]);
        self::assertSame('jwt-provider', $r->getName());
        self::assertCount(1, $r->getVerifyClaims());
    }

    // --- IntentionPermission ---

    public function testIntentionPermissionDefaults(): void
    {
        $p = new IntentionPermission();
        self::assertNull($p->getHTTP());
        self::assertNull($p->getJWT());
    }

    public function testIntentionPermissionWithParams(): void
    {
        $http = new IntentionHTTPPermission(PathPrefix: '/api');
        $p = new IntentionPermission(Action: 'allow', HTTP: $http);
        self::assertSame($http, $p->getHTTP());
    }

    // --- ServiceResolverSubset ---

    public function testServiceResolverSubsetDefaults(): void
    {
        $s = new ServiceResolverSubset();
        self::assertSame('', $s->getFilter());
        self::assertFalse($s->isOnlyPassing());
    }

    public function testServiceResolverSubsetWithParams(): void
    {
        $s = new ServiceResolverSubset(Filter: 'Service.Meta.version == v1', OnlyPassing: true);
        self::assertSame('Service.Meta.version == v1', $s->getFilter());
        self::assertTrue($s->isOnlyPassing());
    }

    // --- ServiceResolverRedirect ---

    public function testServiceResolverRedirectDefaults(): void
    {
        $r = new ServiceResolverRedirect();
        self::assertSame('', $r->getService());
        self::assertSame('', $r->getServiceSubset());
        self::assertSame('', $r->getNamespace());
        self::assertSame('', $r->getPartition());
        self::assertSame('', $r->getDatacenter());
    }

    public function testServiceResolverRedirectWithParams(): void
    {
        $r = new ServiceResolverRedirect(Service: 'web-v2', Datacenter: 'dc2');
        self::assertSame('web-v2', $r->getService());
        self::assertSame('dc2', $r->getDatacenter());
    }

    // --- ServiceResolverFailoverPolicy ---

    public function testServiceResolverFailoverPolicyDefaults(): void
    {
        $p = new ServiceResolverFailoverPolicy();
        self::assertSame('', $p->getMode());
    }

    public function testServiceResolverFailoverPolicyWithParams(): void
    {
        $p = new ServiceResolverFailoverPolicy(Mode: 'sequential', Regions: ['us-west-1']);
        self::assertSame('sequential', $p->getMode());
    }

    // --- ServiceResolverFailoverTarget ---

    public function testServiceResolverFailoverTargetDefaults(): void
    {
        $t = new ServiceResolverFailoverTarget();
        self::assertSame('', $t->getService());
        self::assertSame('', $t->getServiceSubset());
        self::assertSame('', $t->getPartition());
        self::assertSame('', $t->getNamespace());
        self::assertSame('', $t->getDatacenter());
    }

    public function testServiceResolverFailoverTargetWithParams(): void
    {
        $t = new ServiceResolverFailoverTarget(Service: 'web', Datacenter: 'dc2', Peer: 'peer1');
        self::assertSame('web', $t->getService());
        self::assertSame('dc2', $t->getDatacenter());
    }

    // --- ServiceResolverPrioritizeByLocality ---

    public function testServiceResolverPrioritizeByLocalityDefaults(): void
    {
        $p = new ServiceResolverPrioritizeByLocality();
        self::assertSame('', $p->getMode());
    }

    public function testServiceResolverPrioritizeByLocalityWithParams(): void
    {
        $p = new ServiceResolverPrioritizeByLocality(Mode: 'failover');
        self::assertSame('failover', $p->getMode());
    }
}

