<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IngressGatewayConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\IngressListener;
use DCarbone\PHPConsulAPI\ConfigEntry\IngressService;
use DCarbone\PHPConsulAPI\ConfigEntry\IngressServiceConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\TerminatingGatewayConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\LinkedService;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ProxyConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceIntentionsConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\SourceIntention;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouterConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRoute;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteMatch;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatch;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchHeader;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchQueryParam;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteDestination;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplitterConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplit;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailover;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverTarget;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverRedirect;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverSubset;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConfigEntryTypesTest extends TestCase
{
    // --- MeshConfigEntry ---

    public function testMeshConfigEntryWithTransparentProxy(): void
    {
        $tp = new \DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyMeshConfig(MeshDestinationsOnly: true);
        $m = new MeshConfigEntry(TransparentProxy: $tp);
        self::assertSame($tp, $m->getTransparentProxy());
        self::assertTrue($m->getTransparentProxy()->isMeshDestinationsOnly());
    }

    // --- IngressGatewayConfigEntry ---

    public function testIngressGatewayConfigEntryDefaults(): void
    {
        $e = new IngressGatewayConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame([], $e->getListeners());
        self::assertNull($e->getDefaults());
    }

    public function testIngressGatewayConfigEntryWithParams(): void
    {
        $listener = new IngressListener(Port: 8080, Protocol: 'http');
        $e = new IngressGatewayConfigEntry(
            Kind: 'ingress-gateway',
            Name: 'my-gateway',
            Listeners: [$listener],
        );
        self::assertSame('ingress-gateway', $e->getKind());
        self::assertSame('my-gateway', $e->getName());
        self::assertCount(1, $e->getListeners());
    }

    // --- IngressListener ---

    public function testIngressListenerDefaults(): void
    {
        $l = new IngressListener();
        self::assertSame(0, $l->getPort());
        self::assertSame('', $l->getProtocol());
        self::assertSame([], $l->getServices());
        self::assertNull($l->getTLS());
    }

    public function testIngressListenerWithParams(): void
    {
        $svc = new IngressService(Name: 'web', Hosts: ['web.example.com']);
        $l = new IngressListener(Port: 8080, Protocol: 'http', Services: [$svc]);
        self::assertSame(8080, $l->getPort());
        self::assertCount(1, $l->getServices());
    }

    // --- IngressService ---

    public function testIngressServiceDefaults(): void
    {
        $s = new IngressService();
        self::assertSame('', $s->getName());
        self::assertSame([], $s->getHosts());
        self::assertSame('', $s->getNamespace());
        self::assertNull($s->getTLS());
        self::assertNull($s->getMaxConnections());
    }

    public function testIngressServiceWithParams(): void
    {
        $s = new IngressService(Name: 'web', Hosts: ['web.example.com'], MaxConnections: 100);
        self::assertSame('web', $s->getName());
        self::assertSame(['web.example.com'], $s->getHosts());
        self::assertSame(100, $s->getMaxConnections());
    }

    // --- IngressServiceConfig ---

    public function testIngressServiceConfigDefaults(): void
    {
        $c = new IngressServiceConfig();
        self::assertNull($c->getMaxConnections());
        self::assertNull($c->getMaxPendingRequests());
        self::assertNull($c->getMaxConcurrentRequests());
        self::assertNull($c->getPassiveHealthCheck());
    }

    // --- TerminatingGatewayConfigEntry ---

    public function testTerminatingGatewayConfigEntryDefaults(): void
    {
        $e = new TerminatingGatewayConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame([], $e->getServices());
    }

    public function testTerminatingGatewayConfigEntryWithParams(): void
    {
        $svc = new LinkedService(Name: 'external-db');
        $e = new TerminatingGatewayConfigEntry(Kind: 'terminating-gateway', Name: 'tgw', Services: [$svc]);
        self::assertSame('terminating-gateway', $e->getKind());
        self::assertCount(1, $e->getServices());
    }

    // --- ServiceRoute ---

    public function testServiceRouteDefaults(): void
    {
        $r = new ServiceRoute();
        self::assertNull($r->getMatch());
        self::assertNull($r->getDestination());
    }

    public function testServiceRouteWithParams(): void
    {
        $match = new ServiceRouteMatch();
        $dest = new ServiceRouteDestination(Service: 'web-v2');
        $r = new ServiceRoute(Match: $match, Destination: $dest);
        self::assertSame($match, $r->getMatch());
        self::assertSame($dest, $r->getDestination());
    }

    // --- ServiceRouteMatch ---

    public function testServiceRouteMatchDefaults(): void
    {
        $m = new ServiceRouteMatch();
        self::assertNull($m->getHTTP());
    }

    // --- ServiceRouteHTTPMatch ---

    public function testServiceRouteHTTPMatchDefaults(): void
    {
        $h = new ServiceRouteHTTPMatch();
        self::assertSame('', $h->getPathExact());
        self::assertSame('', $h->getPathPrefix());
        self::assertSame('', $h->getPathRegex());
        self::assertSame([], $h->getHeader());
        self::assertSame([], $h->getQueryParam());
        self::assertSame([], $h->getMethods());
    }

    public function testServiceRouteHTTPMatchWithParams(): void
    {
        $h = new ServiceRouteHTTPMatch(PathPrefix: '/api', Methods: ['GET', 'POST']);
        self::assertSame('/api', $h->getPathPrefix());
        self::assertSame(['GET', 'POST'], $h->getMethods());
    }

    // --- ServiceRouteHTTPMatchHeader ---

    public function testServiceRouteHTTPMatchHeaderDefaults(): void
    {
        $h = new ServiceRouteHTTPMatchHeader();
        self::assertSame('', $h->getName());
        self::assertFalse($h->isPresent());
        self::assertSame('', $h->getExact());
    }

    public function testServiceRouteHTTPMatchHeaderWithParams(): void
    {
        $h = new ServiceRouteHTTPMatchHeader(Name: 'x-version', Exact: 'v2');
        self::assertSame('x-version', $h->getName());
        self::assertSame('v2', $h->getExact());
    }

    // --- ServiceRouteHTTPMatchQueryParam ---

    public function testServiceRouteHTTPMatchQueryParamDefaults(): void
    {
        $q = new ServiceRouteHTTPMatchQueryParam();
        self::assertSame('', $q->getName());
        self::assertFalse($q->isPresent());
        self::assertSame('', $q->getExact());
        self::assertSame('', $q->getRegex());
    }

    public function testServiceRouteHTTPMatchQueryParamWithParams(): void
    {
        $q = new ServiceRouteHTTPMatchQueryParam(Name: 'env', Exact: 'prod');
        self::assertSame('env', $q->getName());
        self::assertSame('prod', $q->getExact());
    }

    // --- ServiceRouteDestination ---

    public function testServiceRouteDestinationDefaults(): void
    {
        $d = new ServiceRouteDestination();
        self::assertSame('', $d->getService());
        self::assertSame('', $d->getServiceSubset());
        self::assertSame('', $d->getNamespace());
        self::assertSame(0, $d->getNumRetries());
        self::assertFalse($d->isRetryOnConnectFailure());
    }

    public function testServiceRouteDestinationWithParams(): void
    {
        $d = new ServiceRouteDestination(
            Service: 'web-v2',
            PrefixRewrite: '/v2',
            NumRetries: 3,
            RetryOnConnectFailure: true,
            RetryOnStatusCodes: [503, 504],
        );
        self::assertSame('web-v2', $d->getService());
        self::assertSame('/v2', $d->getPrefixRewrite());
        self::assertSame(3, $d->getNumRetries());
        self::assertTrue($d->isRetryOnConnectFailure());
        self::assertSame([503, 504], $d->getRetryOnStatusCodes());
    }

    // --- ServiceSplit ---

    public function testServiceSplitDefaults(): void
    {
        $s = new ServiceSplit();
        self::assertSame(0.0, $s->getWeight());
        self::assertSame('', $s->getService());
        self::assertSame('', $s->getServiceSubset());
        self::assertSame('', $s->getNamespace());
        self::assertSame('', $s->getPartition());
    }

    public function testServiceSplitWithParams(): void
    {
        $s = new ServiceSplit(Weight: 50.0, Service: 'web-v2', Namespace: 'default');
        self::assertSame(50.0, $s->getWeight());
        self::assertSame('web-v2', $s->getService());
    }

    // --- ServiceRouterConfigEntry ---

    public function testServiceRouterConfigEntryDefaults(): void
    {
        $e = new ServiceRouterConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame([], $e->getRoutes());
    }

    public function testServiceRouterConfigEntryWithParams(): void
    {
        $route = new ServiceRoute(Destination: new ServiceRouteDestination(Service: 'web'));
        $e = new ServiceRouterConfigEntry(Kind: 'service-router', Name: 'web', Routes: [$route]);
        self::assertSame('service-router', $e->getKind());
        self::assertCount(1, $e->getRoutes());
    }

    // --- ServiceSplitterConfigEntry ---

    public function testServiceSplitterConfigEntryDefaults(): void
    {
        $e = new ServiceSplitterConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame([], $e->getSplits());
    }

    public function testServiceSplitterConfigEntryWithParams(): void
    {
        $split = new ServiceSplit(Weight: 100.0, Service: 'web');
        $e = new ServiceSplitterConfigEntry(Kind: 'service-splitter', Name: 'web', Splits: [$split]);
        self::assertSame('service-splitter', $e->getKind());
        self::assertCount(1, $e->getSplits());
    }

    // --- ServiceResolverConfigEntry ---

    public function testServiceResolverConfigEntryDefaults(): void
    {
        $e = new ServiceResolverConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertNull($e->getRedirect());
        self::assertNull($e->getLoadBalancer());
    }

    public function testServiceResolverConfigEntryWithParams(): void
    {
        $redirect = new ServiceResolverRedirect(Service: 'web-v2', Datacenter: 'dc2');
        $e = new ServiceResolverConfigEntry(Kind: 'service-resolver', Name: 'web', Redirect: $redirect);
        self::assertSame('service-resolver', $e->getKind());
        self::assertSame($redirect, $e->getRedirect());
    }

    // --- ServiceResolverFailover ---

    public function testServiceResolverFailoverDefaults(): void
    {
        $f = new ServiceResolverFailover();
        self::assertSame('', $f->getService());
        self::assertSame('', $f->getServiceSubset());
        self::assertSame('', $f->getNamespace());
        self::assertSame([], $f->getDatacenters());
        self::assertSame([], $f->getTargets());
    }

    public function testServiceResolverFailoverWithParams(): void
    {
        $target = new ServiceResolverFailoverTarget(Datacenter: 'dc2');
        $f = new ServiceResolverFailover(Service: 'web', Datacenters: ['dc2'], Targets: [$target]);
        self::assertSame('web', $f->getService());
        self::assertSame(['dc2'], $f->getDatacenters());
        self::assertCount(1, $f->getTargets());
    }

    // --- SourceIntention ---

    public function testSourceIntentionDefaults(): void
    {
        $s = new SourceIntention();
        self::assertSame('', $s->getName());
        self::assertSame('', $s->getPeer());
        self::assertSame('', $s->getPartition());
        self::assertSame('', $s->getNamespace());
        self::assertSame([], $s->getPermissions());
        self::assertSame(0, $s->getPrecedence());
        self::assertSame('', $s->getDescription());
    }

    public function testSourceIntentionWithParams(): void
    {
        $s = new SourceIntention(Name: 'web', Namespace: 'default', Action: 'allow', Description: 'Allow web');
        self::assertSame('web', $s->getName());
        self::assertSame('default', $s->getNamespace());
        self::assertSame('Allow web', $s->getDescription());
    }

    // --- ServiceIntentionsConfigEntry ---

    public function testServiceIntentionsConfigEntryDefaults(): void
    {
        $e = new ServiceIntentionsConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame([], $e->getSources());
    }

    public function testServiceIntentionsConfigEntryWithParams(): void
    {
        $source = new SourceIntention(Name: 'web', Action: 'allow');
        $e = new ServiceIntentionsConfigEntry(Kind: 'service-intentions', name: 'api', Sources: [$source]);
        self::assertSame('service-intentions', $e->getKind());
        self::assertCount(1, $e->getSources());
    }

    // --- ProxyConfigEntry ---

    public function testProxyConfigEntryDefaults(): void
    {
        $e = new ProxyConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame([], $e->getConfig());
    }

    // --- ServiceConfigEntry ---

    public function testServiceConfigEntryDefaults(): void
    {
        $e = new ServiceConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getProtocol());
    }
}

