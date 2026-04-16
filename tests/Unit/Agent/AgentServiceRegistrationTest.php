<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentServiceCheck;
use DCarbone\PHPConsulAPI\Agent\AgentServiceChecks;
use DCarbone\PHPConsulAPI\Agent\AgentServiceConnect;
use DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig;
use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;
use DCarbone\PHPConsulAPI\Agent\AgentWeights;
use DCarbone\PHPConsulAPI\Agent\ServiceKind;
use DCarbone\PHPConsulAPI\Agent\ServicePort;
use DCarbone\PHPConsulAPI\Catalog\ServiceAddress;
use DCarbone\PHPConsulAPI\Peering\Locality;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServiceRegistrationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new AgentServiceRegistration();
        self::assertSame(ServiceKind::Typical, $r->getKind());
        self::assertSame('', $r->getID());
        self::assertSame('', $r->getName());
        self::assertSame([], $r->getTags());
        self::assertSame(0, $r->getPort());
        self::assertSame([], $r->getPorts());
        self::assertSame('', $r->getAddress());
        self::assertSame('', $r->getSocketPath());
        self::assertNull($r->getTaggedAddresses());
        self::assertFalse($r->isEnableTagOverride());
        self::assertNull($r->getMeta());
        self::assertNull($r->getWeights());
        self::assertNull($r->getCheck());
        self::assertInstanceOf(AgentServiceChecks::class, $r->getChecks());
        self::assertNull($r->getProxy());
        self::assertNull($r->getConnect());
        self::assertSame('', $r->getNamespace());
        self::assertSame('', $r->getPartition());
        self::assertNull($r->getLocality());
    }

    public function testConstructorWithAllParams(): void
    {
        $port1 = new ServicePort(Name: 'http', Port: 8080, Default: true);
        $port2 = new ServicePort(Name: 'grpc', Port: 9090);
        $weights = new AgentWeights(Passing: 10, Warning: 1);
        $check = new AgentServiceCheck();
        $checks = new AgentServiceChecks();
        $proxy = new AgentServiceConnectProxyConfig();
        $connect = new AgentServiceConnect();
        $locality = new Locality(Region: 'us-east-1', Zone: 'us-east-1a');

        $r = new AgentServiceRegistration(
            Kind: ServiceKind::ConnectProxy,
            ID: 'svc-1',
            Name: 'web',
            Tags: ['v1', 'primary'],
            Port: 8080,
            Ports: [$port1, $port2],
            Address: '10.0.0.1',
            SocketPath: '/tmp/svc.sock',
            EnableTagOverride: true,
            Meta: ['env' => 'prod'],
            Weights: $weights,
            Check: $check,
            Checks: $checks,
            Proxy: $proxy,
            Connect: $connect,
            Namespace: 'default',
            Partition: 'primary',
            Locality: $locality,
        );
        self::assertSame(ServiceKind::ConnectProxy, $r->getKind());
        self::assertSame('svc-1', $r->getID());
        self::assertSame('web', $r->getName());
        self::assertSame(['v1', 'primary'], $r->getTags());
        self::assertSame(8080, $r->getPort());
        self::assertCount(2, $r->getPorts());
        self::assertSame('10.0.0.1', $r->getAddress());
        self::assertSame('/tmp/svc.sock', $r->getSocketPath());
        self::assertTrue($r->isEnableTagOverride());
        self::assertSame(['env' => 'prod'], $r->getMeta());
        self::assertSame($weights, $r->getWeights());
        self::assertSame($check, $r->getCheck());
        self::assertSame($checks, $r->getChecks());
        self::assertSame($proxy, $r->getProxy());
        self::assertSame($connect, $r->getConnect());
        self::assertSame('default', $r->getNamespace());
        self::assertSame('primary', $r->getPartition());
        self::assertSame($locality, $r->getLocality());
    }

    public function testConstructorWithStringKind(): void
    {
        $r = new AgentServiceRegistration(Kind: 'connect-proxy');
        self::assertSame(ServiceKind::ConnectProxy, $r->getKind());
    }

    public function testSetters(): void
    {
        $r = new AgentServiceRegistration();

        $r->setKind(ServiceKind::ConnectProxy);
        self::assertSame(ServiceKind::ConnectProxy, $r->getKind());

        $r->setKind('mesh-gateway');
        self::assertSame(ServiceKind::MeshGateway, $r->getKind());

        $r->setID('id-1');
        self::assertSame('id-1', $r->getID());

        $r->setName('svc');
        self::assertSame('svc', $r->getName());

        $r->setTags('a', 'b');
        self::assertSame(['a', 'b'], $r->getTags());

        $r->setPort(443);
        self::assertSame(443, $r->getPort());

        $sp = new ServicePort(Name: 'https', Port: 443);
        $r->setPorts($sp);
        self::assertCount(1, $r->getPorts());
        self::assertSame('https', $r->getPorts()[0]->Name);

        $r->setAddress('127.0.0.1');
        self::assertSame('127.0.0.1', $r->getAddress());

        $r->setSocketPath('/run/svc.sock');
        self::assertSame('/run/svc.sock', $r->getSocketPath());

        $r->setEnableTagOverride(true);
        self::assertTrue($r->isEnableTagOverride());

        $r->setMeta(['k' => 'v']);
        self::assertSame(['k' => 'v'], $r->getMeta());

        $w = new AgentWeights(Passing: 5, Warning: 2);
        $r->setWeights($w);
        self::assertSame($w, $r->getWeights());

        $locality = new Locality(Region: 'eu-west-1');
        $r->setLocality($locality);
        self::assertSame($locality, $r->getLocality());

        $r->setNamespace('ns');
        self::assertSame('ns', $r->getNamespace());

        $r->setPartition('part');
        self::assertSame('part', $r->getPartition());
    }

    public function testJsonSerializeOmitsDefaults(): void
    {
        $r = new AgentServiceRegistration();
        $out = $r->jsonSerialize();
        self::assertObjectNotHasProperty('Kind', $out);
        self::assertObjectNotHasProperty('ID', $out);
        self::assertObjectNotHasProperty('Name', $out);
        self::assertObjectNotHasProperty('Tags', $out);
        self::assertObjectNotHasProperty('Port', $out);
        self::assertObjectNotHasProperty('Ports', $out);
        self::assertObjectNotHasProperty('Address', $out);
        self::assertObjectNotHasProperty('SocketPath', $out);
    }

    public function testJsonSerializeWithValues(): void
    {
        $sp = new ServicePort(Name: 'http', Port: 8080);
        $r = new AgentServiceRegistration(
            ID: 'svc',
            Name: 'web',
            Port: 80,
            Ports: [$sp],
            SocketPath: '/tmp/web.sock',
        );
        $out = $r->jsonSerialize();
        self::assertSame('svc', $out->ID);
        self::assertSame('web', $out->Name);
        self::assertSame(80, $out->Port);
        self::assertCount(1, $out->Ports);
        self::assertSame('/tmp/web.sock', $out->SocketPath);
    }

    public function testJsonUnserialize(): void
    {
        $data = new \stdClass();
        $data->Kind = 'connect-proxy';
        $data->ID = 'svc-1';
        $data->Name = 'web';
        $data->Tags = ['v1'];
        $data->Port = 8080;
        $data->SocketPath = '/tmp/svc.sock';
        $data->Address = '10.0.0.1';
        $data->EnableTagOverride = true;
        $data->Namespace = 'default';
        $data->Partition = 'primary';

        $portObj = new \stdClass();
        $portObj->Name = 'grpc';
        $portObj->Port = 9090;
        $portObj->Default = false;
        $data->Ports = [$portObj];

        $localityObj = new \stdClass();
        $localityObj->Region = 'us-east-1';
        $localityObj->Zone = 'us-east-1a';
        $data->Locality = $localityObj;

        $r = AgentServiceRegistration::jsonUnserialize($data);
        self::assertSame(ServiceKind::ConnectProxy, $r->getKind());
        self::assertSame('svc-1', $r->getID());
        self::assertSame('web', $r->getName());
        self::assertSame(['v1'], $r->getTags());
        self::assertSame(8080, $r->getPort());
        self::assertSame('/tmp/svc.sock', $r->getSocketPath());
        self::assertSame('10.0.0.1', $r->getAddress());
        self::assertTrue($r->isEnableTagOverride());
        self::assertSame('default', $r->getNamespace());
        self::assertSame('primary', $r->getPartition());
        self::assertCount(1, $r->getPorts());
        self::assertSame('grpc', $r->getPorts()[0]->Name);
        self::assertSame(9090, $r->getPorts()[0]->Port);
        self::assertSame('us-east-1', $r->getLocality()->Region);
    }

    public function testToString(): void
    {
        $r = new AgentServiceRegistration(Name: 'my-service');
        self::assertSame('my-service', (string)$r);
    }
}
