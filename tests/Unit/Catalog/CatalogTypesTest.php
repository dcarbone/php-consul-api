<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Catalog\ServiceAddress;
use DCarbone\PHPConsulAPI\Catalog\Weights;
use DCarbone\PHPConsulAPI\Catalog\CompoundServiceName;
use DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration;
use DCarbone\PHPConsulAPI\Catalog\CatalogNode;
use DCarbone\PHPConsulAPI\Catalog\CatalogNodeServiceList;
use DCarbone\PHPConsulAPI\Catalog\CatalogRegistration;
use DCarbone\PHPConsulAPI\Catalog\CatalogService;
use DCarbone\PHPConsulAPI\Catalog\GatewayService;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CatalogTypesTest extends TestCase
{
    // --- Node ---

    public function testNodeDefaults(): void
    {
        $n = new Node();
        self::assertSame('', $n->getID());
        self::assertSame('', $n->getNode());
        self::assertSame('', $n->getAddress());
        self::assertSame('', $n->getDatacenter());
        self::assertSame(0, $n->getCreateIndex());
        self::assertSame(0, $n->getModifyIndex());
        self::assertSame('', $n->getPartition());
    }

    public function testNodeWithParams(): void
    {
        $n = new Node(ID: 'id-1', Node: 'node1', Address: '10.0.0.1', Datacenter: 'dc1', CreateIndex: 1, ModifyIndex: 2);
        self::assertSame('id-1', $n->getID());
        self::assertSame('node1', $n->getNode());
        self::assertSame('10.0.0.1', $n->getAddress());
        self::assertSame('dc1', $n->getDatacenter());
    }

    public function testNodeJsonSerialize(): void
    {
        $n = new Node(ID: 'x', Node: 'y', Address: 'z');
        $out = $n->jsonSerialize();
        self::assertSame('x', $out->ID);
        self::assertSame('y', $out->Node);
    }

    public function testNodeJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->ID = 'id';
        $d->Node = 'node';
        $d->Address = 'addr';
        $d->Datacenter = 'dc';
        $d->TaggedAddresses = new \stdClass();
        $d->Meta = new \stdClass();
        $d->CreateIndex = 5;
        $d->ModifyIndex = 10;
        $n = Node::jsonUnserialize($d);
        self::assertSame('id', $n->getID());
        self::assertSame('node', $n->getNode());
    }

    // --- ServiceAddress ---

    public function testServiceAddressDefaults(): void
    {
        $sa = new ServiceAddress();
        self::assertSame('', $sa->getAddress());
        self::assertSame(0, $sa->getPort());
    }

    public function testServiceAddressWithParams(): void
    {
        $sa = new ServiceAddress(Address: '10.0.0.1', Port: 8080);
        self::assertSame('10.0.0.1', $sa->getAddress());
        self::assertSame(8080, $sa->getPort());
    }

    public function testServiceAddressFluentSetters(): void
    {
        $sa = new ServiceAddress();
        $result = $sa->setAddress('a')->setPort(1);
        self::assertSame($sa, $result);
        self::assertSame('a', $sa->getAddress());
        self::assertSame(1, $sa->getPort());
    }

    public function testServiceAddressJsonRoundTrip(): void
    {
        $sa = new ServiceAddress(Address: 'a', Port: 1);
        $out = $sa->jsonSerialize();
        self::assertSame('a', $out->Address);
        $d = new \stdClass();
        $d->Address = 'b';
        $d->Port = 2;
        $sa2 = ServiceAddress::jsonUnserialize($d);
        self::assertSame('b', $sa2->getAddress());
    }

    // --- Weights ---

    public function testWeightsDefaults(): void
    {
        $w = new Weights();
        self::assertSame(0, $w->getPassing());
        self::assertSame(0, $w->getWarning());
    }

    public function testWeightsWithParams(): void
    {
        $w = new Weights(Passing: 10, Warning: 1);
        self::assertSame(10, $w->getPassing());
        self::assertSame(1, $w->getWarning());
    }

    public function testWeightsFluentSetters(): void
    {
        $w = new Weights();
        $result = $w->setPassing(5)->setWarning(2);
        self::assertSame($w, $result);
    }

    public function testWeightsJsonRoundTrip(): void
    {
        $w = new Weights(Passing: 3, Warning: 1);
        $out = $w->jsonSerialize();
        self::assertSame(3, $out->Passing);
        $d = new \stdClass();
        $d->Passing = 4;
        $d->Warning = 2;
        $w2 = Weights::jsonUnserialize($d);
        self::assertSame(4, $w2->getPassing());
    }

    // --- CompoundServiceName ---

    public function testCompoundServiceNameDefaults(): void
    {
        $c = new CompoundServiceName();
        self::assertSame('', $c->getName());
        self::assertSame('', $c->getNamespace());
        self::assertSame('', $c->getPartition());
    }

    public function testCompoundServiceNameWithParams(): void
    {
        $c = new CompoundServiceName(Name: 'web', Namespace: 'ns', Partition: 'pt');
        self::assertSame('web', $c->getName());
        self::assertSame('ns', $c->getNamespace());
        self::assertSame('pt', $c->getPartition());
    }

    public function testCompoundServiceNameFluentSetters(): void
    {
        $c = new CompoundServiceName();
        $result = $c->setName('n')->setNamespace('ns')->setPartition('pt');
        self::assertSame($c, $result);
    }

    // --- CatalogDeregistration ---

    public function testCatalogDeregistrationDefaults(): void
    {
        $d = new CatalogDeregistration();
        self::assertSame('', $d->getNode());
        self::assertSame('', $d->getAddress());
        self::assertSame('', $d->getDatacenter());
        self::assertSame('', $d->getServiceID());
        self::assertSame('', $d->getCheckID());
        self::assertSame('', $d->getNamespace());
        self::assertSame('', $d->getPartition());
    }

    public function testCatalogDeregistrationWithParams(): void
    {
        $d = new CatalogDeregistration(Node: 'n', Address: 'a', Datacenter: 'dc', ServiceID: 's', CheckID: 'c');
        self::assertSame('n', $d->getNode());
        self::assertSame('a', $d->getAddress());
        self::assertSame('dc', $d->getDatacenter());
        self::assertSame('s', $d->getServiceID());
        self::assertSame('c', $d->getCheckID());
    }

    public function testCatalogDeregistrationJsonSerialize(): void
    {
        $d = new CatalogDeregistration(Node: 'n', ServiceID: 's');
        $out = $d->jsonSerialize();
        self::assertSame('n', $out->Node);
        self::assertSame('s', $out->ServiceID);
    }

    // --- CatalogNode ---

    public function testCatalogNodeDefaults(): void
    {
        $cn = new CatalogNode();
        self::assertNull($cn->getNode());
        self::assertSame([], $cn->getServices());
    }

    public function testCatalogNodeWithParams(): void
    {
        $node = new Node(ID: 'n1', Node: 'node1');
        $cn = new CatalogNode(Node: $node);
        self::assertSame($node, $cn->getNode());
    }

    // --- CatalogNodeServiceList ---

    public function testCatalogNodeServiceListDefaults(): void
    {
        $l = new CatalogNodeServiceList();
        self::assertNull($l->getNode());
        self::assertSame([], $l->getServices());
    }

    public function testCatalogNodeServiceListWithParams(): void
    {
        $node = new Node(ID: 'n1');
        $svc = new AgentService(ID: 'svc-1', Service: 'web');
        $l = new CatalogNodeServiceList(Node: $node, Services: [$svc]);
        self::assertSame($node, $l->getNode());
        self::assertCount(1, $l->getServices());
    }

    // --- CatalogRegistration ---

    public function testCatalogRegistrationDefaults(): void
    {
        $r = new CatalogRegistration();
        self::assertSame('', $r->getID());
        self::assertSame('', $r->getNode());
        self::assertSame('', $r->getAddress());
        self::assertSame('', $r->getDatacenter());
        self::assertNull($r->getService());
        self::assertNull($r->getCheck());
        self::assertFalse($r->isSkipNodeUpdate());
    }

    public function testCatalogRegistrationWithParams(): void
    {
        $r = new CatalogRegistration(
            ID: 'id-1',
            Node: 'node1',
            Address: '10.0.0.1',
            Datacenter: 'dc1',
            SkipNodeUpdate: true,
        );
        self::assertSame('id-1', $r->getID());
        self::assertSame('node1', $r->getNode());
        self::assertTrue($r->isSkipNodeUpdate());
    }

    // --- CatalogService ---

    public function testCatalogServiceDefaults(): void
    {
        $s = new CatalogService();
        self::assertSame('', $s->getID());
        self::assertSame('', $s->getNode());
        self::assertSame('', $s->getAddress());
        self::assertSame('', $s->getServiceID());
        self::assertSame('', $s->getServiceName());
        self::assertSame(0, $s->getServicePort());
        self::assertSame(0, $s->getCreateIndex());
        self::assertSame(0, $s->getModifyIndex());
    }

    public function testCatalogServiceWithParams(): void
    {
        $s = new CatalogService(
            ID: 'id',
            Node: 'node1',
            Address: '10.0.0.1',
            ServiceID: 'web-1',
            ServiceName: 'web',
            ServicePort: 8080,
            ServiceTags: ['v1'],
        );
        self::assertSame('web-1', $s->getServiceID());
        self::assertSame('web', $s->getServiceName());
        self::assertSame(8080, $s->getServicePort());
        self::assertSame(['v1'], $s->getServiceTags());
    }

    public function testCatalogServiceJsonSerialize(): void
    {
        $s = new CatalogService(ServiceID: 'web', ServiceName: 'web', ServicePort: 80);
        $out = $s->jsonSerialize();
        self::assertSame('web', $out->ServiceID);
        self::assertSame(80, $out->ServicePort);
    }

    // --- GatewayService ---

    public function testGatewayServiceDefaults(): void
    {
        $g = new GatewayService();
        self::assertInstanceOf(CompoundServiceName::class, $g->getGateway());
        self::assertInstanceOf(CompoundServiceName::class, $g->getService());
        self::assertSame(0, $g->getPort());
        self::assertSame('', $g->getProtocol());
        self::assertSame([], $g->getHosts());
    }

    public function testGatewayServiceWithParams(): void
    {
        $gw = new CompoundServiceName(Name: 'gw');
        $svc = new CompoundServiceName(Name: 'web');
        $g = new GatewayService(
            Gateway: $gw,
            Service: $svc,
            Port: 443,
            Protocol: 'http',
            Hosts: ['web.example.com'],
        );
        self::assertSame('gw', $g->getGateway()->getName());
        self::assertSame('web', $g->getService()->getName());
        self::assertSame(443, $g->getPort());
        self::assertSame(['web.example.com'], $g->getHosts());
    }
}

