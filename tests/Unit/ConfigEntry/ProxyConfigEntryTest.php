<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ProxyConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ProxyMode;
use DCarbone\PHPConsulAPI\ConfigEntry\MutualTLSMode;
use DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\AccessLogsConfig;
use DCarbone\PHPConsulAPI\Consul;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ProxyConfigEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new ProxyConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame(Consul::ProxyConfigGlobal, $e->getName());
        self::assertSame('', $e->getPartition());
        self::assertSame(ProxyMode::Default, $e->getMode());
        self::assertNull($e->getTransparentProxy());
        self::assertSame(MutualTLSMode::Default, $e->getMutualTLSMode());
        self::assertSame([], $e->getConfig());
        self::assertNull($e->getAccessLogs());
        self::assertSame([], $e->getEnvoyExtensions());
        self::assertNull($e->getFailoverPolicy());
        self::assertNull($e->getPrioritizeByLocality());
    }

    public function testConstructorWithParams(): void
    {
        $tp = new TransparentProxyConfig(OutboundListenerPort: 15001);
        $al = new AccessLogsConfig(Enabled: true);
        $e = new ProxyConfigEntry(
            Kind: 'proxy-defaults',
            Name: 'global',
            Partition: 'pt',
            Mode: ProxyMode::Transparent,
            TransparentProxy: $tp,
            MutualTLSMode: MutualTLSMode::Strict,
            Config: ['protocol' => 'http'],
            AccessLogs: $al,
        );
        self::assertSame('proxy-defaults', $e->getKind());
        self::assertSame('pt', $e->getPartition());
        self::assertSame(ProxyMode::Transparent, $e->getMode());
        self::assertSame($tp, $e->getTransparentProxy());
        self::assertSame(MutualTLSMode::Strict, $e->getMutualTLSMode());
        self::assertSame(['protocol' => 'http'], $e->getConfig());
        self::assertSame($al, $e->getAccessLogs());
    }

    public function testFluentSetters(): void
    {
        $e = new ProxyConfigEntry();
        $result = $e->setKind('proxy-defaults')
            ->setPartition('pt')
            ->setMode(ProxyMode::Direct)
            ->setMutualTLSMode(MutualTLSMode::Permissive)
            ->setConfig(['protocol' => 'tcp']);
        self::assertSame($e, $result);
        self::assertSame('proxy-defaults', $e->getKind());
        self::assertSame('pt', $e->getPartition());
        self::assertSame(ProxyMode::Direct, $e->getMode());
        self::assertSame(MutualTLSMode::Permissive, $e->getMutualTLSMode());
        self::assertSame(['protocol' => 'tcp'], $e->getConfig());
    }

    public function testConstructorWithEnumAsString(): void
    {
        $e = new ProxyConfigEntry(Mode: 'transparent', MutualTLSMode: 'strict');
        self::assertSame(ProxyMode::Transparent, $e->getMode());
        self::assertSame(MutualTLSMode::Strict, $e->getMutualTLSMode());
    }
}
