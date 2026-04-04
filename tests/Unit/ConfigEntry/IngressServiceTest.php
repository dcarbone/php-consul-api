<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IngressService;
use DCarbone\PHPConsulAPI\ConfigEntry\GatewayServiceTLSConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class IngressServiceTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new IngressService();
        self::assertSame('', $s->getName());
        self::assertSame([], $s->getHosts());
        self::assertSame('', $s->getNamespace());
        self::assertSame('', $s->getPartition());
        self::assertNull($s->getTLS());
        self::assertNull($s->getMaxConnections());
        self::assertNull($s->getMaxPendingRequests());
        self::assertNull($s->getMaxConcurrentRequests());
    }

    public function testConstructorWithParams(): void
    {
        $s = new IngressService(
            Name: 'web',
            Hosts: ['web.example.com'],
            Namespace: 'ns',
            Partition: 'pt',
            MaxConnections: 100,
            MaxPendingRequests: 50,
            MaxConcurrentRequests: 25,
        );
        self::assertSame('web', $s->getName());
        self::assertSame(['web.example.com'], $s->getHosts());
        self::assertSame('ns', $s->getNamespace());
        self::assertSame('pt', $s->getPartition());
        self::assertSame(100, $s->getMaxConnections());
        self::assertSame(50, $s->getMaxPendingRequests());
        self::assertSame(25, $s->getMaxConcurrentRequests());
    }

    public function testFluentSetters(): void
    {
        $s = new IngressService();
        $result = $s->setName('api')
            ->setHosts('api.example.com')
            ->setNamespace('ns')
            ->setPartition('pt')
            ->setMaxConnections(200);
        self::assertSame($s, $result);
        self::assertSame('api', $s->getName());
        self::assertSame(['api.example.com'], $s->getHosts());
        self::assertSame('ns', $s->getNamespace());
        self::assertSame('pt', $s->getPartition());
        self::assertSame(200, $s->getMaxConnections());
    }

}
