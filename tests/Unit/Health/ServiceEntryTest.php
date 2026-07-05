<?php

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Health\ServiceEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $se = new ServiceEntry();
        self::assertNull($se->getNode());
        self::assertNull($se->Node);
        self::assertNull($se->getService());
        self::assertNull($se->Service);
        self::assertInstanceOf(HealthChecks::class, $se->getChecks());
        self::assertInstanceOf(HealthChecks::class, $se->Checks);
        self::assertCount(0, $se->Checks);
    }

    public function testConstructorWithValues(): void
    {
        $node = new Node(Node: 'n1', Address: '10.0.0.1');
        $svc = new AgentService(ID: 's1', Service: 'web');
        $checks = new HealthChecks(new HealthCheck(Name: 'c1'));
        $se = new ServiceEntry(Node: $node, Service: $svc, Checks: $checks);
        self::assertSame($node, $se->getNode());
        self::assertSame($node, $se->Node);
        self::assertSame('n1', $se->Node->getNode());
        self::assertSame($svc, $se->getService());
        self::assertSame($svc, $se->Service);
        self::assertSame('s1', $se->Service->getID());
        self::assertSame($checks, $se->getChecks());
        self::assertSame($checks, $se->Checks);
        self::assertCount(1, $se->Checks);
    }

    public function testSettersWithDirectFieldAccess(): void
    {
        $se = new ServiceEntry();

        $node = new Node(Node: 'x');
        $se->setNode($node);
        self::assertSame($node, $se->getNode());
        self::assertSame($node, $se->Node);

        $svc = new AgentService(ID: 'y');
        $se->setService($svc);
        self::assertSame($svc, $se->getService());
        self::assertSame($svc, $se->Service);

        $checks = new HealthChecks(new HealthCheck(Name: 'z'));
        $se->setChecks($checks);
        self::assertSame($checks, $se->getChecks());
        self::assertSame($checks, $se->Checks);
    }

    public function testSetNodeToNull(): void
    {
        $se = new ServiceEntry(Node: new Node(Node: 'n'));
        $se->setNode(null);
        self::assertNull($se->getNode());
        self::assertNull($se->Node);
    }

    public function testSetServiceToNull(): void
    {
        $se = new ServiceEntry(Service: new AgentService(ID: 's'));
        $se->setService(null);
        self::assertNull($se->getService());
        self::assertNull($se->Service);
    }

    public function testSetChecksToNullCreatesEmptyHealthChecks(): void
    {
        $se = new ServiceEntry(Checks: new HealthChecks(new HealthCheck()));
        $se->setChecks(null);
        self::assertInstanceOf(HealthChecks::class, $se->getChecks());
        self::assertCount(0, $se->Checks);
    }

    public function testFluentSetters(): void
    {
        $se = new ServiceEntry();
        $result = $se
            ->setNode(new Node(Node: 'x'))
            ->setService(new AgentService(ID: 'y'))
            ->setChecks(new HealthChecks());
        self::assertSame($se, $result);
    }

    public function testJsonSerialize(): void
    {
        $se = new ServiceEntry(
            Node: new Node(Node: 'n'),
            Service: new AgentService(ID: 's'),
        );
        $out = $se->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertObjectHasProperty('Node', $out);
        self::assertObjectHasProperty('Service', $out);
        self::assertObjectHasProperty('Checks', $out);
    }

    public function testJsonUnserialize(): void
    {
        $nodeObj = new \stdClass();
        $nodeObj->Node = 'json-node';
        $nodeObj->Address = '10.0.0.5';
        $nodeObj->Datacenter = '';
        $nodeObj->TaggedAddresses = new \stdClass();
        $nodeObj->Meta = new \stdClass();
        $nodeObj->CreateIndex = 0;
        $nodeObj->ModifyIndex = 0;
        $nodeObj->Partition = '';
        $nodeObj->PeerName = '';

        $svcObj = new \stdClass();
        $svcObj->ID = 'json-svc';
        $svcObj->Service = 'web';
        $svcObj->Tags = [];
        $svcObj->Meta = new \stdClass();
        $svcObj->Port = 8080;
        $svcObj->Address = '';
        $svcObj->Weights = new \stdClass();
        $svcObj->Weights->Passing = 1;
        $svcObj->Weights->Warning = 1;
        $svcObj->EnableTagOverride = false;
        $svcObj->CreateIndex = 0;
        $svcObj->ModifyIndex = 0;
        $svcObj->Namespace = '';
        $svcObj->Partition = '';
        $svcObj->PeerName = '';

        $decoded = new \stdClass();
        $decoded->Node = $nodeObj;
        $decoded->Service = $svcObj;
        $decoded->Checks = [];

        $se = ServiceEntry::jsonUnserialize($decoded);
        self::assertNotNull($se->Node);
        self::assertSame('json-node', $se->Node->getNode());
        self::assertNotNull($se->Service);
        self::assertSame('json-svc', $se->Service->getID());
        self::assertInstanceOf(HealthChecks::class, $se->Checks);
        self::assertCount(0, $se->Checks);
    }

    public function testJsonUnserializeWithNullNodeAndService(): void
    {
        $decoded = new \stdClass();
        $decoded->Node = null;
        $decoded->Service = null;
        $decoded->Checks = [];

        $se = ServiceEntry::jsonUnserialize($decoded);
        self::assertNull($se->Node);
        self::assertNull($se->Service);
        self::assertInstanceOf(HealthChecks::class, $se->Checks);
    }
}
