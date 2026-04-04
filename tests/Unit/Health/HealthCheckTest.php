<?php

declare(strict_types=1);

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
        self::assertSame('', $hc->getCheckID());
        self::assertSame('', $hc->getName());
        self::assertSame('', $hc->getStatus());
        self::assertSame('', $hc->getNotes());
        self::assertSame('', $hc->getOutput());
        self::assertSame('', $hc->getServiceID());
        self::assertSame('', $hc->getServiceName());
        self::assertSame([], $hc->getServiceTags());
        self::assertSame('', $hc->getType());
        self::assertSame('', $hc->getNamespace());
        self::assertSame('', $hc->getPartition());
        self::assertSame(0, $hc->getExposedPort());
        self::assertSame(0, $hc->getCreateIndex());
        self::assertSame(0, $hc->getModifyIndex());
    }

    public function testConstructorWithValues(): void
    {
        $hc = new HealthCheck(
            Node: 'node-1',
            CheckID: 'check-1',
            Name: 'my-check',
            Status: 'passing',
            ServiceID: 'svc-1',
            ServiceName: 'web',
            ServiceTags: ['v1'],
            CreateIndex: 5,
            ModifyIndex: 10,
        );
        self::assertSame('node-1', $hc->getNode());
        self::assertSame('check-1', $hc->getCheckID());
        self::assertSame('my-check', $hc->getName());
        self::assertSame('passing', $hc->getStatus());
        self::assertSame('svc-1', $hc->getServiceID());
        self::assertSame(['v1'], $hc->getServiceTags());
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
            ->setCreateIndex(1)
            ->setModifyIndex(2);
        self::assertSame($hc, $result);
        self::assertSame('n', $hc->getNode());
        self::assertSame(['t1', 't2'], $hc->getServiceTags());
    }

    public function testJsonSerialize(): void
    {
        $hc = new HealthCheck(Node: 'n', CheckID: 'c', Status: 'passing');
        $out = $hc->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('n', $out->Node);
        self::assertSame('passing', $out->Status);
    }

    public function testJsonUnserialize(): void
    {
        $defObj = new \stdClass();
        $defObj->HTTP = '';
        $defObj->Method = '';
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
        $decoded->Notes = '';
        $decoded->Output = '';
        $decoded->ServiceID = '';
        $decoded->ServiceName = '';
        $decoded->ServiceTags = [];
        $decoded->Type = '';
        $decoded->Namespace = '';
        $decoded->Partition = '';
        $decoded->ExposedPort = 0;
        $decoded->PeerName = '';
        $decoded->Definition = $defObj;
        $decoded->CreateIndex = 5;
        $decoded->ModifyIndex = 10;

        $hc = HealthCheck::jsonUnserialize($decoded);
        self::assertSame('json-node', $hc->getNode());
        self::assertSame('json-check', $hc->getCheckID());
        self::assertSame('critical', $hc->getStatus());
        self::assertInstanceOf(HealthCheckDefinition::class, $hc->getDefinition());
    }
}

