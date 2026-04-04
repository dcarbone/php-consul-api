<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\AccessLogsConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\CookieConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\DestinationConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\ExposePath;
use DCarbone\PHPConsulAPI\ConfigEntry\GatewayServiceTLSConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\GatewayTLSConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\GatewayTLSSDSConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyMeshConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\PeeringMeshConfig;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SimpleConfigEntryTypesTest extends TestCase
{
    // --- AccessLogsConfig ---

    public function testAccessLogsConfigDefaults(): void
    {
        $c = new AccessLogsConfig();
        self::assertFalse($c->isEnabled());
        self::assertFalse($c->isDisableListenerLogs());
        self::assertSame('', $c->getPath());
        self::assertSame('', $c->getJSONFormat());
        self::assertSame('', $c->getTextFormat());
    }

    public function testAccessLogsConfigWithParams(): void
    {
        $c = new AccessLogsConfig(Enabled: true, Path: '/var/log/envoy.log');
        self::assertTrue($c->isEnabled());
        self::assertSame('/var/log/envoy.log', $c->getPath());
    }

    // --- CookieConfig ---

    public function testCookieConfigDefaults(): void
    {
        $c = new CookieConfig();
        self::assertFalse($c->getSession());
        self::assertSame(0, $c->getTTL()->Nanoseconds());
        self::assertSame('', $c->getPath());
    }

    public function testCookieConfigWithParams(): void
    {
        $c = new CookieConfig(Session: true, TTL: '5m', Path: '/');
        self::assertTrue($c->getSession());
        self::assertSame('/', $c->getPath());
    }

    // --- DestinationConfig ---

    public function testDestinationConfigDefaults(): void
    {
        $c = new DestinationConfig();
        self::assertSame([], $c->getAddresses());
        self::assertSame(0, $c->getPort());
    }

    public function testDestinationConfigWithParams(): void
    {
        $c = new DestinationConfig(Addresses: ['10.0.0.1', '10.0.0.2'], Port: 443);
        self::assertSame(['10.0.0.1', '10.0.0.2'], $c->getAddresses());
        self::assertSame(443, $c->getPort());
    }

    // --- ExposeConfig ---

    public function testExposeConfigDefaults(): void
    {
        $c = new ExposeConfig();
        self::assertFalse($c->isChecks());
        self::assertSame([], $c->getPaths());
    }

    public function testExposeConfigWithParams(): void
    {
        $path = new ExposePath(ListenerPort: 21500, Path: '/health', Protocol: 'http');
        $c = new ExposeConfig(Checks: true, Paths: [$path]);
        self::assertTrue($c->isChecks());
        self::assertCount(1, $c->getPaths());
    }

    // --- ExposePath ---

    public function testExposePathDefaults(): void
    {
        $p = new ExposePath();
        self::assertSame(0, $p->getListenerPort());
        self::assertSame('', $p->getPath());
        self::assertSame(0, $p->getLocalPathPort());
        self::assertSame('', $p->getProtocol());
        self::assertFalse($p->isParsedFromCheck());
    }

    public function testExposePathWithParams(): void
    {
        $p = new ExposePath(ListenerPort: 21500, Path: '/health', LocalPathPort: 8080, Protocol: 'http');
        self::assertSame(21500, $p->getListenerPort());
        self::assertSame('/health', $p->getPath());
        self::assertSame(8080, $p->getLocalPathPort());
        self::assertSame('http', $p->getProtocol());
    }

    // --- GatewayTLSSDSConfig ---

    public function testGatewayTLSSDSConfigDefaults(): void
    {
        $c = new GatewayTLSSDSConfig();
        self::assertSame('', $c->getClusterName());
        self::assertSame('', $c->getCertResource());
    }

    public function testGatewayTLSSDSConfigWithParams(): void
    {
        $c = new GatewayTLSSDSConfig(ClusterName: 'cluster', CertResource: 'cert');
        self::assertSame('cluster', $c->getClusterName());
        self::assertSame('cert', $c->getCertResource());
    }

    // --- GatewayServiceTLSConfig ---

    public function testGatewayServiceTLSConfigDefaults(): void
    {
        $c = new GatewayServiceTLSConfig();
        self::assertNull($c->getSDS());
    }

    public function testGatewayServiceTLSConfigWithParams(): void
    {
        $sds = new GatewayTLSSDSConfig(ClusterName: 'c', CertResource: 'r');
        $c = new GatewayServiceTLSConfig(SDS: $sds);
        self::assertSame($sds, $c->getSDS());
    }

    // --- GatewayTLSConfig ---

    public function testGatewayTLSConfigDefaults(): void
    {
        $c = new GatewayTLSConfig();
        self::assertFalse($c->isEnabled());
        self::assertNull($c->getSDS());
        self::assertSame('', $c->getTLSMinVersion());
        self::assertSame('', $c->getTLSMaxVersion());
        self::assertSame([], $c->getCipherSuites());
    }

    public function testGatewayTLSConfigWithParams(): void
    {
        $c = new GatewayTLSConfig(Enabled: true, TLSMinVersion: 'TLSv1_2', CipherSuites: ['suite1']);
        self::assertTrue($c->isEnabled());
        self::assertSame('TLSv1_2', $c->getTLSMinVersion());
        self::assertSame(['suite1'], $c->getCipherSuites());
    }

    // --- MeshGatewayConfig ---

    public function testMeshGatewayConfigDefaults(): void
    {
        $c = new MeshGatewayConfig();
        self::assertNotNull($c->getMode());
    }

    // --- TransparentProxyConfig ---

    public function testTransparentProxyConfigDefaults(): void
    {
        $c = new TransparentProxyConfig();
        self::assertSame(0, $c->getOutboundListenerPort());
        self::assertFalse($c->isDialedDirectly());
    }

    public function testTransparentProxyConfigWithParams(): void
    {
        $c = new TransparentProxyConfig(OutboundListenerPort: 15001, DialedDirectly: true);
        self::assertSame(15001, $c->getOutboundListenerPort());
        self::assertTrue($c->isDialedDirectly());
    }

    public function testTransparentProxyConfigFluentSetters(): void
    {
        $c = new TransparentProxyConfig();
        $result = $c->setOutboundListenerPort(15001)->setDialedDirectly(true);
        self::assertSame($c, $result);
    }

    // --- TransparentProxyMeshConfig ---

    public function testTransparentProxyMeshConfigDefaults(): void
    {
        $c = new TransparentProxyMeshConfig();
        self::assertFalse($c->isMeshDestinationsOnly());
    }

    public function testTransparentProxyMeshConfigWithParams(): void
    {
        $c = new TransparentProxyMeshConfig(MeshDestinationsOnly: true);
        self::assertTrue($c->isMeshDestinationsOnly());
    }

    // --- PeeringMeshConfig ---

    public function testPeeringMeshConfigDefaults(): void
    {
        $c = new PeeringMeshConfig();
        self::assertFalse($c->isPeerThroughMeshGateways());
    }

    public function testPeeringMeshConfigWithParams(): void
    {
        $c = new PeeringMeshConfig(PeerThroughMeshGateways: true);
        self::assertTrue($c->isPeerThroughMeshGateways());
    }
}

