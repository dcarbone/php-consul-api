<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentCheckRegistrationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new AgentCheckRegistration();
        self::assertSame('', $r->getID());
        self::assertSame('', $r->ID);
        self::assertSame('', $r->getServiceID());
        self::assertSame('', $r->ServiceID);
        self::assertSame('', $r->getNamespace());
        self::assertSame('', $r->Namespace);
        self::assertSame('', $r->getPartition());
        self::assertSame('', $r->Partition);
        // inherited from AgentServiceCheck
        self::assertSame('', $r->getCheckID());
        self::assertSame('', $r->getName());
        self::assertSame([], $r->getArgs());
    }

    public function testConstructorWithParams(): void
    {
        $r = new AgentCheckRegistration(
            ID: 'chk-1',
            ServiceID: 'svc-1',
            CheckID: 'chk-id',
            Name: 'health',
            Interval: '10s',
            HTTP: 'http://localhost:8080/health',
            Namespace: 'ns-1',
            Partition: 'pt-1',
        );
        self::assertSame('chk-1', $r->getID());
        self::assertSame('chk-1', $r->ID);
        self::assertSame('svc-1', $r->getServiceID());
        self::assertSame('ns-1', $r->getNamespace());
        self::assertSame('pt-1', $r->getPartition());
        // inherited
        self::assertSame('chk-id', $r->getCheckID());
        self::assertSame('health', $r->getName());
        self::assertSame('10s', $r->getInterval());
        self::assertSame('http://localhost:8080/health', $r->getHTTP());
    }

    public function testFluentSetters(): void
    {
        $r = new AgentCheckRegistration();
        $result = $r->setID('id-1')
            ->setServiceID('svc-1')
            ->setNamespace('ns')
            ->setPartition('pt');
        self::assertSame($r, $result);
        self::assertSame('id-1', $r->getID());
        self::assertSame('id-1', $r->ID);
        self::assertSame('svc-1', $r->getServiceID());
        self::assertSame('svc-1', $r->ServiceID);
        self::assertSame('ns', $r->getNamespace());
        self::assertSame('ns', $r->Namespace);
        self::assertSame('pt', $r->getPartition());
        self::assertSame('pt', $r->Partition);
    }

    public function testJsonSerializeOmitsDefaults(): void
    {
        $r = new AgentCheckRegistration();
        $out = $r->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertObjectNotHasProperty('ID', $out);
        self::assertObjectNotHasProperty('ServiceID', $out);
        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
    }

    public function testJsonSerializeWithValues(): void
    {
        $r = new AgentCheckRegistration(
            ID: 'chk-1',
            ServiceID: 'svc-1',
            CheckID: 'cid',
            HTTP: 'http://localhost/health',
            Interval: '5s',
            Namespace: 'ns',
            Partition: 'pt',
        );
        $out = $r->jsonSerialize();
        self::assertSame('chk-1', $out->ID);
        self::assertSame('svc-1', $out->ServiceID);
        self::assertSame('ns', $out->Namespace);
        self::assertSame('pt', $out->Partition);
        self::assertSame('cid', $out->CheckID);
        self::assertSame('http://localhost/health', $out->HTTP);
        self::assertSame('5s', $out->Interval);
    }
}

