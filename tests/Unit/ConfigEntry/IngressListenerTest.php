<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IngressListener;
use DCarbone\PHPConsulAPI\ConfigEntry\IngressService;
use DCarbone\PHPConsulAPI\ConfigEntry\GatewayTLSConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class IngressListenerTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $l = new IngressListener();
        self::assertSame(0, $l->getPort());
        self::assertSame('', $l->getProtocol());
        self::assertSame([], $l->getServices());
        self::assertNull($l->getTLS());
    }

    public function testConstructorWithParams(): void
    {
        $svc = new IngressService(Name: 'web', Hosts: ['web.example.com']);
        $tls = new GatewayTLSConfig(Enabled: true);
        $l = new IngressListener(Port: 8080, Protocol: 'http', Services: [$svc], TLS: $tls);
        self::assertSame(8080, $l->getPort());
        self::assertSame('http', $l->getProtocol());
        self::assertCount(1, $l->getServices());
        self::assertSame($tls, $l->getTLS());
    }

    public function testFluentSetters(): void
    {
        $svc = new IngressService(Name: 'api');
        $l = new IngressListener();
        $result = $l->setPort(443)
            ->setProtocol('tcp')
            ->setServices($svc);
        self::assertSame($l, $result);
        self::assertSame(443, $l->getPort());
        self::assertSame('tcp', $l->getProtocol());
        self::assertCount(1, $l->getServices());
    }

}
