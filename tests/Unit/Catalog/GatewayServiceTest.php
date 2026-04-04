<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Agent\ServiceKind;
use DCarbone\PHPConsulAPI\Catalog\CompoundServiceName;
use DCarbone\PHPConsulAPI\Catalog\GatewayService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GatewayServiceTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $g = new GatewayService();
        self::assertInstanceOf(CompoundServiceName::class, $g->getGateway());
        self::assertInstanceOf(CompoundServiceName::class, $g->getService());
        self::assertSame(ServiceKind::Typical, $g->getGatewayKind());
        self::assertSame(0, $g->getPort());
        self::assertSame(0, $g->Port);
        self::assertSame('', $g->getProtocol());
        self::assertSame([], $g->getHosts());
        self::assertSame('', $g->getCAFile());
        self::assertSame('', $g->getCertFile());
        self::assertSame('', $g->getKeyFile());
        self::assertSame('', $g->getSNI());
        self::assertSame('', $g->getFromWildCard());
    }

    public function testConstructorWithParams(): void
    {
        $gw = new CompoundServiceName(Name: 'gw');
        $svc = new CompoundServiceName(Name: 'web');
        $g = new GatewayService(
            Gateway: $gw,
            Service: $svc,
            GatewayKind: ServiceKind::IngressGateway,
            Port: 443,
            Protocol: 'http',
            Hosts: ['web.example.com'],
            CAFile: '/ca.pem',
            CertFile: '/cert.pem',
            KeyFile: '/key.pem',
            SNI: 'web.consul',
            FromWildCard: 'true',
        );
        self::assertSame('gw', $g->getGateway()->getName());
        self::assertSame('web', $g->getService()->getName());
        self::assertSame(ServiceKind::IngressGateway, $g->getGatewayKind());
        self::assertSame(443, $g->getPort());
        self::assertSame('http', $g->getProtocol());
        self::assertSame(['web.example.com'], $g->getHosts());
        self::assertSame('/ca.pem', $g->getCAFile());
        self::assertSame('web.consul', $g->getSNI());
    }

    public function testConstructorWithStringGatewayKind(): void
    {
        $g = new GatewayService(GatewayKind: 'ingress-gateway');
        self::assertSame(ServiceKind::IngressGateway, $g->getGatewayKind());
    }

    public function testFluentSetters(): void
    {
        $g = new GatewayService();
        $gw = new CompoundServiceName(Name: 'gw');
        $svc = new CompoundServiceName(Name: 'web');
        $result = $g->setGateway($gw)
            ->setService($svc)
            ->setGatewayKind(ServiceKind::MeshGateway)
            ->setPort(443)
            ->setProtocol('http')
            ->setHosts('a.com', 'b.com')
            ->setCAFile('/ca')
            ->setCertFile('/cert')
            ->setKeyFile('/key')
            ->setSNI('sni')
            ->setFromWildCard('wc');
        self::assertSame($g, $result);
        self::assertSame('gw', $g->getGateway()->getName());
        self::assertSame(ServiceKind::MeshGateway, $g->getGatewayKind());
        self::assertSame(443, $g->getPort());
        self::assertSame(['a.com', 'b.com'], $g->getHosts());
    }

    public function testJsonSerialize(): void
    {
        $g = new GatewayService(
            Gateway: new CompoundServiceName(Name: 'gw'),
            Service: new CompoundServiceName(Name: 'web'),
            Port: 443,
            Protocol: 'http',
            Hosts: ['web.example.com'],
        );
        $out = $g->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('', $out->GatewayKind);
        self::assertSame(443, $out->Port);
        self::assertSame('http', $out->Protocol);
        self::assertSame(['web.example.com'], $out->Hosts);
    }

    public function testJsonSerializeOmitsEmptyOptionalFields(): void
    {
        $g = new GatewayService();
        $out = $g->jsonSerialize();
        self::assertObjectNotHasProperty('Port', $out);
        self::assertObjectNotHasProperty('Protocol', $out);
        self::assertObjectNotHasProperty('Hosts', $out);
        self::assertObjectNotHasProperty('CAFile', $out);
        self::assertObjectNotHasProperty('CertFile', $out);
        self::assertObjectNotHasProperty('KeyFile', $out);
        self::assertObjectNotHasProperty('SNI', $out);
        self::assertObjectNotHasProperty('FromWildCard', $out);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $gwObj = new \stdClass();
        $gwObj->Name = 'gw';
        $gwObj->Namespace = '';
        $gwObj->Partition = '';
        $d->Gateway = $gwObj;
        $svcObj = new \stdClass();
        $svcObj->Name = 'web';
        $svcObj->Namespace = '';
        $svcObj->Partition = '';
        $d->Service = $svcObj;
        $d->GatewayKind = 'ingress-gateway';
        $d->Port = 443;
        $d->Protocol = 'http';
        $d->Hosts = ['a.com'];
        $d->CAFile = '';
        $d->CertFile = '';
        $d->KeyFile = '';
        $d->SNI = '';
        $d->FromWildCard = '';

        $g = GatewayService::jsonUnserialize($d);
        self::assertInstanceOf(GatewayService::class, $g);
        self::assertSame('gw', $g->getGateway()->getName());
        self::assertSame('web', $g->getService()->getName());
        self::assertSame(ServiceKind::IngressGateway, $g->getGatewayKind());
        self::assertSame(443, $g->getPort());
        self::assertSame(['a.com'], $g->getHosts());
    }
}

