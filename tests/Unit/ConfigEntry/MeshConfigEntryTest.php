<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\MeshConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyMeshConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshTLSConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshHTTPConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\PeeringMeshConfig;
use DCarbone\PHPConsulAPI\Consul;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class MeshConfigEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new MeshConfigEntry();
        self::assertSame(Consul::MeshConfig, $m->getKind());
        self::assertSame(Consul::MeshConfigMesh, $m->getName());
        self::assertSame('', $m->Partition);
        self::assertInstanceOf(TransparentProxyMeshConfig::class, $m->getTransparentProxy());
        self::assertFalse($m->isAllowEnablingPermissiveMutualTLS());
        self::assertNull($m->getTLS());
        self::assertNull($m->getHTTP());
        self::assertNull($m->getPeering());
    }

    public function testConstructorWithParams(): void
    {
        $tp = new TransparentProxyMeshConfig(MeshDestinationsOnly: true);
        $tls = new MeshTLSConfig();
        $http = new MeshHTTPConfig(SanitizeXForwardClientCert: true);
        $peering = new PeeringMeshConfig(PeerThroughMeshGateways: true);
        $m = new MeshConfigEntry(
            Partition: 'pt',
            TransparentProxy: $tp,
            AllowEnablingPermissiveMutualTLS: true,
            TLS: $tls,
            HTTP: $http,
            Peering: $peering,
        );
        self::assertSame($tp, $m->getTransparentProxy());
        self::assertTrue($m->isAllowEnablingPermissiveMutualTLS());
        self::assertSame($tls, $m->getTLS());
        self::assertSame($http, $m->getHTTP());
        self::assertSame($peering, $m->getPeering());
    }

    public function testFluentSetters(): void
    {
        $tp = new TransparentProxyMeshConfig(MeshDestinationsOnly: true);
        $m = new MeshConfigEntry();
        $result = $m->setTransparentProxy($tp)
            ->setAllowEnablingPermissiveMutualTLS(true);
        self::assertSame($m, $result);
        self::assertSame($tp, $m->getTransparentProxy());
        self::assertTrue($m->isAllowEnablingPermissiveMutualTLS());
    }

}
