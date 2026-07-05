<?php

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Txn\ServiceOp;
use DCarbone\PHPConsulAPI\Txn\ServiceTxnOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceTxnOpTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $op = new ServiceTxnOp();
        self::assertSame(ServiceOp::UNDEFINED, $op->getVerb());
        self::assertSame(ServiceOp::UNDEFINED, $op->Verb);
        self::assertSame('', $op->getNode());
        self::assertSame('', $op->Node);
        self::assertInstanceOf(AgentService::class, $op->getService());
        self::assertInstanceOf(AgentService::class, $op->Service);
    }

    public function testConstructorWithEnumVerb(): void
    {
        $svc = new AgentService(ID: 'svc-1', Service: 'web');
        $op = new ServiceTxnOp(Verb: ServiceOp::ServiceGet, Node: 'node-1', Service: $svc);
        self::assertSame(ServiceOp::ServiceGet, $op->getVerb());
        self::assertSame('node-1', $op->getNode());
        self::assertSame('svc-1', $op->getService()->getID());
    }

    public function testConstructorWithStringVerb(): void
    {
        $op = new ServiceTxnOp(Verb: 'set');
        self::assertSame(ServiceOp::ServiceSet, $op->getVerb());
    }

    public function testConstructorWithInvalidStringVerbThrows(): void
    {
        $this->expectException(\ValueError::class);
        new ServiceTxnOp(Verb: 'not-valid');
    }

    public function testSetVerbWithString(): void
    {
        $op = new ServiceTxnOp();
        $result = $op->setVerb('cas');
        self::assertSame($op, $result);
        self::assertSame(ServiceOp::ServiceCAS, $op->getVerb());
    }

    public function testSetVerbWithEnum(): void
    {
        $op = new ServiceTxnOp();
        $op->setVerb(ServiceOp::ServiceDelete);
        self::assertSame(ServiceOp::ServiceDelete, $op->getVerb());
    }

    public function testFluentSetters(): void
    {
        $op = new ServiceTxnOp();
        $svc = new AgentService(ID: 'x');
        $result = $op
            ->setVerb(ServiceOp::ServiceSet)
            ->setNode('n')
            ->setService($svc);
        self::assertSame($op, $result);
        self::assertSame(ServiceOp::ServiceSet, $op->getVerb());
        self::assertSame('n', $op->getNode());
        self::assertSame('x', $op->getService()->getID());
    }

    public function testJsonSerialize(): void
    {
        $op = new ServiceTxnOp(Verb: ServiceOp::ServiceGet, Node: 'n', Service: new AgentService(ID: 's'));
        $out = $op->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(ServiceOp::ServiceGet, $out->Verb);
        self::assertSame('n', $out->Node);
    }

    public function testJsonUnserialize(): void
    {
        $svcObj = new \stdClass();
        $svcObj->ID = 'svc-1';
        $svcObj->Service = 'web';
        $svcObj->Tags = [];
        $svcObj->Port = 8080;
        $svcObj->Address = '';
        $svcObj->Meta = new \stdClass();
        $svcObj->Weights = new \stdClass();
        $svcObj->Weights->Passing = 1;
        $svcObj->Weights->Warning = 1;
        $svcObj->EnableTagOverride = false;
        $svcObj->Namespace = '';
        $svcObj->Partition = '';
        $svcObj->Datacenter = '';
        $svcObj->PeerName = '';
        $svcObj->ContentHash = '';
        $svcObj->CreateIndex = 0;
        $svcObj->ModifyIndex = 0;

        $decoded = new \stdClass();
        $decoded->Verb = 'get';
        $decoded->Node = 'n1';
        $decoded->Service = $svcObj;

        $op = ServiceTxnOp::jsonUnserialize($decoded);
        self::assertSame(ServiceOp::ServiceGet, $op->getVerb());
        self::assertSame('n1', $op->getNode());
        self::assertSame('svc-1', $op->getService()->getID());
    }

    public function testJsonRoundTrip(): void
    {
        $op = new ServiceTxnOp(
            Verb: ServiceOp::ServiceCAS,
            Node: 'rt-node',
            Service: new AgentService(ID: 'rt-svc'),
        );
        $decoded = json_decode(json_encode($op));
        $restored = ServiceTxnOp::jsonUnserialize($decoded);
        self::assertSame(ServiceOp::ServiceCAS, $restored->getVerb());
        self::assertSame('rt-node', $restored->getNode());
        self::assertSame('rt-svc', $restored->getService()->getID());
    }
}

