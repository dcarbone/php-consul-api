<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class UpstreamConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $u = new UpstreamConfig();
        self::assertSame('', $u->getName());
        self::assertSame('', $u->getPartition());
        self::assertSame('', $u->getNamespace());
        self::assertSame('', $u->getProtocol());
        self::assertSame(0, $u->getConnectTimeoutMs());
    }

    public function testConstructorWithParams(): void
    {
        $u = new UpstreamConfig(
            Name: 'db',
            Partition: 'pt',
            Namespace: 'ns',
            Protocol: 'tcp',
            ConnectTimeoutMs: 5000,
        );
        self::assertSame('db', $u->getName());
        self::assertSame('pt', $u->getPartition());
        self::assertSame('ns', $u->getNamespace());
        self::assertSame('tcp', $u->getProtocol());
        self::assertSame(5000, $u->getConnectTimeoutMs());
    }

    public function testFluentSetters(): void
    {
        $u = new UpstreamConfig();
        $result = $u->setName('cache')
            ->setPartition('pt')
            ->setNamespace('ns')
            ->setProtocol('http')
            ->setConnectTimeoutMs(3000);
        self::assertSame($u, $result);
        self::assertSame('cache', $u->getName());
        self::assertSame('pt', $u->getPartition());
        self::assertSame('ns', $u->getNamespace());
        self::assertSame('http', $u->getProtocol());
        self::assertSame(3000, $u->getConnectTimeoutMs());
    }

}
