<?php

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Health\HealthCheckDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HealthCheckTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $hc = new HealthCheck();
        self::assertSame('', $hc->getNode());
        self::assertSame('', $hc->Node);
        self::assertSame('', $hc->getCheckID());
        self::assertSame('', $hc->CheckID);
        self::assertSame('', $hc->getName());
        self::assertSame('', $hc->Name);
        self::assertSame('', $hc->getStatus());
        self::assertSame('', $hc->Status);
        self::assertSame('', $hc->getNotes());
        self::assertSame('', $hc->Notes);
        self::assertSame('', $hc->getOutput());
        self::assertSame('', $hc->Output);
        self::assertSame('', $hc->getServiceID());
        self::assertSame('', $hc->ServiceID);
        self::assertSame('', $hc->getServiceName());
        self::assertSame('', $hc->ServiceName);
        self::assertSame([], $hc->getServiceTags());
        self::assertSame([], $hc->ServiceTags);
        self::assertSame('', $hc->getType());
        self::assertSame('', $hc->Type);
        self::assertSame('', $hc->getNamespace());
        self::assertSame('', $hc->Namespace);
        self::assertSame('', $hc->getPartition());
        self::assertSame('', $hc->Partition);
        self::assertSame(0, $hc->getExposedPort());
        self::assertSame(0, $hc->ExposedPort);
        self::assertSame('', $hc->getPeerName());
        self::assertSame('', $hc->PeerName);
        self::assertInstanceOf(HealthCheckDefinition::class, $hc->getDefinition());
        self::assertInstanceOf(HealthCheckDefinition::class, $hc->Definition);
        self::assertSame(0, $hc->getCreateIndex());
        self::assertSame(0, $hc->CreateIndex);
        self::assertSame(0, $hc->getModifyIndex());
        self::assertSame(0, $hc->ModifyIndex);
    }

    public function testConstructorWithValues(): void
    {
        $def = new HealthCheckDefinition(HTTP: 'http://localhost/health');
        $hc = new HealthCheck(
            Node: 'node-1',
            CheckID: 'check-1',
            Name: 'my-check',
            Status: 'passing',
            Notes: 'some notes',
            Output: 'some output',
            ServiceID: 'svc-1',
            ServiceName: 'web',
            ServiceTags: ['v1', 'v2'],
            Type: 'http',
            Namespace: 'default',
            Partition: 'default',
            ExposedPort: 8080,
            PeerName: 'peer-1',
            Definition: $def,
            CreateIndex: 5,
            ModifyIndex: 10,
        );
        self::assertSame('node-1', $hc->getNode());
        self::assertSame('node-1', $hc->Node);
        self::assertSame('check-1', $hc->getCheckID());
        self::assertSame('check-1', $hc->CheckID);
        self::assertSame('my-check', $hc->getName());
        self::assertSame('my-check', $hc->Name);
        self::assertSame('passing', $hc->getStatus());
        self::assertSame('passing', $hc->Status);
        self::assertSame('some notes', $hc->getNotes());
        self::assertSame('some notes', $hc->Notes);
        self::assertSame('some output', $hc->getOutput());
        self::assertSame('some output', $hc->Output);
        self::assertSame('svc-1', $hc->getServiceID());
        self::assertSame('svc-1', $hc->ServiceID);
        self::assertSame('web', $hc->getServiceName());
        self::assertSame('web', $hc->ServiceName);
        self::assertSame(['v1', 'v2'], $hc->getServiceTags());
        self::assertSame(['v1', 'v2'], $hc->ServiceTags);
        self::assertSame('http', $hc->getType());
        self::assertSame('http', $hc->Type);
        self::assertSame('default', $hc->getNamespace());
        self::assertSame('default', $hc->Namespace);
        self::assertSame('default', $hc->getPartition());
        self::assertSame('default', $hc->Partition);
        self::assertSame(8080, $hc->getExposedPort());
        self::assertSame(8080, $hc->ExposedPort);
        self::assertSame('peer-1', $hc->getPeerName());
        self::assertSame('peer-1', $hc->PeerName);
        self::assertSame($def, $hc->getDefinition());
        self::assertSame($def, $hc->Definition);
        self::assertSame(5, $hc->getCreateIndex());
        self::assertSame(5, $hc->CreateIndex);
        self::assertSame(10, $hc->getModifyIndex());
        self::assertSame(10, $hc->ModifyIndex);
    }

    public function testSettersWithDirectFieldAccess(): void
    {
        $hc = new HealthCheck();

        $hc->setNode('n');
        self::assertSame('n', $hc->getNode());
        self::assertSame('n', $hc->Node);

        $hc->setCheckID('c');
        self::assertSame('c', $hc->getCheckID());
        self::assertSame('c', $hc->CheckID);

        $hc->setName('name');
        self::assertSame('name', $hc->getName());
        self::assertSame('name', $hc->Name);

        $hc->setStatus('critical');
        self::assertSame('critical', $hc->getStatus());
        self::assertSame('critical', $hc->Status);

        $hc->setNotes('notes');
        self::assertSame('notes', $hc->getNotes());
        self::assertSame('notes', $hc->Notes);

        $hc->setOutput('output');
        self::assertSame('output', $hc->getOutput());
        self::assertSame('output', $hc->Output);

        $hc->setServiceID('si');
        self::assertSame('si', $hc->getServiceID());
        self::assertSame('si', $hc->ServiceID);

        $hc->setServiceName('sn');
        self::assertSame('sn', $hc->getServiceName());
        self::assertSame('sn', $hc->ServiceName);

        $hc->setServiceTags('t1', 't2');
        self::assertSame(['t1', 't2'], $hc->getServiceTags());
        self::assertSame(['t1', 't2'], $hc->ServiceTags);

        $hc->setType('tcp');
        self::assertSame('tcp', $hc->getType());
        self::assertSame('tcp', $hc->Type);

        $hc->setNamespace('ns');
        self::assertSame('ns', $hc->getNamespace());
        self::assertSame('ns', $hc->Namespace);

        $hc->setPartition('pt');
        self::assertSame('pt', $hc->getPartition());
        self::assertSame('pt', $hc->Partition);

        $hc->setExposedPort(8080);
        self::assertSame(8080, $hc->getExposedPort());
        self::assertSame(8080, $hc->ExposedPort);

        $hc->setPeerName('peer');
        self::assertSame('peer', $hc->getPeerName());
        self::assertSame('peer', $hc->PeerName);

        $def = new HealthCheckDefinition(HTTP: 'http://test');
        $hc->setDefinition($def);
        self::assertSame($def, $hc->getDefinition());
        self::assertSame($def, $hc->Definition);

        $hc->setCreateIndex(1);
        self::assertSame(1, $hc->getCreateIndex());
        self::assertSame(1, $hc->CreateIndex);

        $hc->setModifyIndex(2);
        self::assertSame(2, $hc->getModifyIndex());
        self::assertSame(2, $hc->ModifyIndex);
    }

    public function testFluentSetters(): void
    {
        $hc = new HealthCheck();
        $result = $hc
            ->setNode('n')
            ->setCheckID('c')
            ->setName('name')
            ->setStatus('critical')
            ->setNotes('notes')
            ->setOutput('output')
            ->setServiceID('si')
            ->setServiceName('sn')
            ->setServiceTags('t1', 't2')
            ->setType('tcp')
            ->setNamespace('ns')
            ->setPartition('pt')
            ->setExposedPort(8080)
            ->setPeerName('peer')
            ->setDefinition(new HealthCheckDefinition())
            ->setCreateIndex(1)
            ->setModifyIndex(2);
        self::assertSame($hc, $result);
    }

    public function testJsonSerialize(): void
    {
        $hc = new HealthCheck(
            Node: 'n',
            CheckID: 'c',
            Status: 'passing',
            Namespace: 'ns',
            Partition: 'pt',
            PeerName: 'peer',
        );
        $out = $hc->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('n', $out->Node);
        self::assertSame('c', $out->CheckID);
        self::assertSame('passing', $out->Status);
        self::assertSame('ns', $out->Namespace);
        self::assertSame('pt', $out->Partition);
        self::assertSame('peer', $out->PeerName);
    }

    public function testJsonSerializeOmitsEmptyOptionalFields(): void
    {
        $hc = new HealthCheck(Node: 'n', Status: 'passing');
        $out = $hc->jsonSerialize();
        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
        self::assertObjectNotHasProperty('PeerName', $out);
    }

    public function testJsonUnserialize(): void
    {
        $defObj = new \stdClass();
        $defObj->HTTP = 'http://test';
        $defObj->Method = 'GET';
        $defObj->Body = '';
        $defObj->TLSSkipVerify = false;
        $defObj->TCP = '';
        $defObj->TCPUseTLS = false;
        $defObj->UDP = '';
        $defObj->GRPC = '';
        $defObj->OSService = '';
        $defObj->GRPCUseTLS = false;
        $defObj->IntervalDuration = 0;
        $defObj->TimeoutDuration = 0;
        $defObj->DeregisterCriticalServiceAfterDuration = 0;

        $decoded = new \stdClass();
        $decoded->Node = 'json-node';
        $decoded->CheckID = 'json-check';
        $decoded->Name = 'json-name';
        $decoded->Status = 'critical';
        $decoded->Notes = 'notes';
        $decoded->Output = 'output';
        $decoded->ServiceID = 'svc-1';
        $decoded->ServiceName = 'web';
        $decoded->ServiceTags = ['v1'];
        $decoded->Type = 'http';
        $decoded->Namespace = 'ns';
        $decoded->Partition = 'pt';
        $decoded->ExposedPort = 9090;
        $decoded->PeerName = 'peer';
        $decoded->Definition = $defObj;
        $decoded->CreateIndex = 5;
        $decoded->ModifyIndex = 10;

        $hc = HealthCheck::jsonUnserialize($decoded);
        self::assertSame('json-node', $hc->Node);
        self::assertSame('json-check', $hc->CheckID);
        self::assertSame('json-name', $hc->Name);
        self::assertSame('critical', $hc->Status);
        self::assertSame('notes', $hc->Notes);
        self::assertSame('output', $hc->Output);
        self::assertSame('svc-1', $hc->ServiceID);
        self::assertSame('web', $hc->ServiceName);
        self::assertSame(['v1'], $hc->ServiceTags);
        self::assertSame('http', $hc->Type);
        self::assertSame('ns', $hc->Namespace);
        self::assertSame('pt', $hc->Partition);
        self::assertSame(9090, $hc->ExposedPort);
        self::assertSame('peer', $hc->PeerName);
        self::assertInstanceOf(HealthCheckDefinition::class, $hc->Definition);
        self::assertSame('http://test', $hc->Definition->HTTP);
        self::assertSame(5, $hc->CreateIndex);
        self::assertSame(10, $hc->ModifyIndex);
    }

    public function testJsonRoundTrip(): void
    {
        $original = new HealthCheck(
            Node: 'rt-node',
            CheckID: 'rt-check',
            Name: 'round-trip',
            Status: 'passing',
            ServiceTags: ['a', 'b'],
            Namespace: 'ns',
            Partition: 'pt',
            PeerName: 'peer',
            CreateIndex: 3,
            ModifyIndex: 7,
        );
        $decoded = json_decode(json_encode($original));
        $restored = HealthCheck::jsonUnserialize($decoded);
        self::assertSame($original->Node, $restored->Node);
        self::assertSame($original->CheckID, $restored->CheckID);
        self::assertSame($original->Name, $restored->Name);
        self::assertSame($original->Status, $restored->Status);
        self::assertSame($original->ServiceTags, $restored->ServiceTags);
        self::assertSame($original->CreateIndex, $restored->CreateIndex);
        self::assertSame($original->ModifyIndex, $restored->ModifyIndex);
    }
}

