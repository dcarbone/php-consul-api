<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension;
use DCarbone\PHPConsulAPI\ConfigEntry\HashPolicy;
use DCarbone\PHPConsulAPI\ConfigEntry\CookieConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\HTTPHeaderModifiers;
use DCarbone\PHPConsulAPI\ConfigEntry\LeastRequestConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\RingHashConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\PassiveHealthCheck;
use DCarbone\PHPConsulAPI\ConfigEntry\UpstreamLimits;
use DCarbone\PHPConsulAPI\ConfigEntry\RateLimits;
use DCarbone\PHPConsulAPI\ConfigEntry\InstanceLevelRateLimits;
use DCarbone\PHPConsulAPI\ConfigEntry\InstanceLevelRouteRateLimits;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshDirectionalTLSConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshHTTPConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshTLSConfig;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MiddleConfigEntryTypesTest extends TestCase
{
    // --- EnvoyExtension ---

    public function testEnvoyExtensionDefaults(): void
    {
        $e = new EnvoyExtension();
        self::assertSame('', $e->getName());
        self::assertFalse($e->isRequired());
        self::assertSame([], $e->getArguments());
        self::assertSame('', $e->getConsulVersion());
        self::assertSame('', $e->getEnvoyVersion());
    }

    public function testEnvoyExtensionWithParams(): void
    {
        $e = new EnvoyExtension(Name: 'lua', Required: true, Arguments: ['key' => 'val'], ConsulVersion: '1.15', EnvoyVersion: '1.25');
        self::assertSame('lua', $e->getName());
        self::assertTrue($e->isRequired());
        self::assertSame(['key' => 'val'], $e->getArguments());
    }

    // --- HashPolicy ---

    public function testHashPolicyDefaults(): void
    {
        $h = new HashPolicy();
        self::assertSame('', $h->getField());
        self::assertSame('', $h->getFieldValue());
        self::assertNull($h->getCookieConfig());
        self::assertFalse($h->isSourceIP());
        self::assertFalse($h->isTerminal());
    }

    public function testHashPolicyWithParams(): void
    {
        $cookie = new CookieConfig(Session: true, Path: '/');
        $h = new HashPolicy(Field: 'header', FieldValue: 'x-user', CookieConfig: $cookie, SourceIP: true, Terminal: true);
        self::assertSame('header', $h->getField());
        self::assertSame('x-user', $h->getFieldValue());
        self::assertSame($cookie, $h->getCookieConfig());
        self::assertTrue($h->isSourceIP());
        self::assertTrue($h->isTerminal());
    }

    // --- HTTPHeaderModifiers ---

    public function testHTTPHeaderModifiersDefaults(): void
    {
        $h = new HTTPHeaderModifiers();
        self::assertSame([], $h->getAdd());
        self::assertSame([], $h->getSet());
        self::assertSame([], $h->getRemove());
    }

    public function testHTTPHeaderModifiersWithParams(): void
    {
        $h = new HTTPHeaderModifiers(
            Add: ['X-Custom' => 'value'],
            Set: ['X-Replace' => 'new-value'],
            Remove: ['X-Remove'],
        );
        self::assertSame(['X-Custom' => 'value'], $h->getAdd());
        self::assertSame(['X-Replace' => 'new-value'], $h->getSet());
        self::assertSame(['X-Remove'], $h->getRemove());
    }

    // --- LeastRequestConfig ---

    public function testLeastRequestConfigDefaults(): void
    {
        $c = new LeastRequestConfig();
        self::assertSame(0, $c->getChoiceCount());
    }

    public function testLeastRequestConfigWithParams(): void
    {
        $c = new LeastRequestConfig(ChoiceCount: 5);
        self::assertSame(5, $c->getChoiceCount());
    }

    // --- RingHashConfig ---

    public function testRingHashConfigDefaults(): void
    {
        $c = new RingHashConfig();
        self::assertSame(0, $c->getMinimumRingSize());
        self::assertSame(0, $c->getMaximumRingSize());
    }

    public function testRingHashConfigWithParams(): void
    {
        $c = new RingHashConfig(MinimumRingSize: 1024, MaximumRingSize: 8192);
        self::assertSame(1024, $c->getMinimumRingSize());
        self::assertSame(8192, $c->getMaximumRingSize());
    }

    // --- PassiveHealthCheck ---

    public function testPassiveHealthCheckDefaults(): void
    {
        $p = new PassiveHealthCheck();
        self::assertSame(0, $p->getInterval()->Nanoseconds());
        self::assertSame(0, $p->getMaxFailures());
        self::assertNull($p->getEnforcingConsecutive5xx());
        self::assertNull($p->getMaxEjectionPercent());
    }

    public function testPassiveHealthCheckWithParams(): void
    {
        $p = new PassiveHealthCheck(Interval: '10s', MaxFailures: 3, EnforcingConsecutive5xx: 100, MaxEjectionPercent: 50);
        self::assertSame(3, $p->getMaxFailures());
        self::assertSame(100, $p->getEnforcingConsecutive5xx());
        self::assertSame(50, $p->getMaxEjectionPercent());
    }

    // --- UpstreamLimits ---

    public function testUpstreamLimitsDefaults(): void
    {
        $u = new UpstreamLimits();
        self::assertNull($u->getMaxConnections());
        self::assertNull($u->getMaxPendingRequests());
        self::assertNull($u->getMaxConcurrentRequests());
    }

    public function testUpstreamLimitsWithParams(): void
    {
        $u = new UpstreamLimits(MaxConnections: 100, MaxPendingRequests: 50, MaxConcurrentRequests: 25);
        self::assertSame(100, $u->getMaxConnections());
        self::assertSame(50, $u->getMaxPendingRequests());
        self::assertSame(25, $u->getMaxConcurrentRequests());
    }

    // --- RateLimits ---

    public function testRateLimitsDefaults(): void
    {
        $r = new RateLimits();
        self::assertInstanceOf(InstanceLevelRateLimits::class, $r->getInstanceLevel());
    }

    // --- InstanceLevelRateLimits ---

    public function testInstanceLevelRateLimitsDefaults(): void
    {
        $i = new InstanceLevelRateLimits();
        self::assertSame(0, $i->getRequestsPerSecond());
        self::assertSame(0, $i->getRequestsMaxBurst());
    }

    public function testInstanceLevelRateLimitsWithParams(): void
    {
        $i = new InstanceLevelRateLimits(RequestsPerSecond: 100, RequestsMaxBurst: 200);
        self::assertSame(100, $i->getRequestsPerSecond());
        self::assertSame(200, $i->getRequestsMaxBurst());
    }

    // --- InstanceLevelRouteRateLimits ---

    public function testInstanceLevelRouteRateLimitsDefaults(): void
    {
        $r = new InstanceLevelRouteRateLimits();
        self::assertSame('', $r->getPathExact());
        self::assertSame('', $r->getPathPrefix());
        self::assertSame('', $r->getPathRegex());
        self::assertSame(0, $r->getRequestsPerSecond());
        self::assertSame(0, $r->getRequestsMaxBurst());
    }

    public function testInstanceLevelRouteRateLimitsWithParams(): void
    {
        $r = new InstanceLevelRouteRateLimits(PathPrefix: '/api', RequestsPerSecond: 50);
        self::assertSame('/api', $r->getPathPrefix());
        self::assertSame(50, $r->getRequestsPerSecond());
    }

    // --- MeshDirectionalTLSConfig ---

    public function testMeshDirectionalTLSConfigDefaults(): void
    {
        $m = new MeshDirectionalTLSConfig();
        self::assertSame('', $m->getTLSMinVersion());
        self::assertSame('', $m->getTLSMaxVersion());
        self::assertSame([], $m->getCipherSuites());
    }

    public function testMeshDirectionalTLSConfigWithParams(): void
    {
        $m = new MeshDirectionalTLSConfig(TLSMinVersion: 'TLSv1_2', TLSMaxVersion: 'TLSv1_3', CipherSuites: ['suite1']);
        self::assertSame('TLSv1_2', $m->getTLSMinVersion());
        self::assertSame(['suite1'], $m->getCipherSuites());
    }

    // --- MeshHTTPConfig ---

    public function testMeshHTTPConfigDefaults(): void
    {
        $m = new MeshHTTPConfig();
        self::assertFalse($m->isSanitizeXForwardClientCert());
    }

    public function testMeshHTTPConfigWithParams(): void
    {
        $m = new MeshHTTPConfig(SanitizeXForwardClientCert: true);
        self::assertTrue($m->isSanitizeXForwardClientCert());
    }

    // --- MeshTLSConfig ---

    public function testMeshTLSConfigDefaults(): void
    {
        $m = new MeshTLSConfig();
        self::assertNull($m->getIncoming());
        self::assertNull($m->getOutgoing());
    }

    public function testMeshTLSConfigWithParams(): void
    {
        $incoming = new MeshDirectionalTLSConfig(TLSMinVersion: 'TLSv1_2');
        $outgoing = new MeshDirectionalTLSConfig(TLSMinVersion: 'TLSv1_3');
        $m = new MeshTLSConfig(Incoming: $incoming, Outgoing: $outgoing);
        self::assertSame($incoming, $m->getIncoming());
        self::assertSame($outgoing, $m->getOutgoing());
    }
}

