<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Health\HealthCheckDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentCheckTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new AgentCheck();
        self::assertSame('', $c->getNode());
        self::assertSame('', $c->getCheckID());
        self::assertSame('', $c->getName());
        self::assertSame('', $c->getStatus());
        self::assertSame('', $c->getNotes());
        self::assertSame('', $c->getOutput());
        self::assertSame('', $c->getServiceID());
        self::assertSame('', $c->getServiceName());
        self::assertSame('', $c->getType());
        self::assertInstanceOf(HealthCheckDefinition::class, $c->getDefinition());
        self::assertSame('', $c->getNamespace());
    }

    public function testConstructorWithParams(): void
    {
        $c = new AgentCheck(Node: 'node1', CheckID: 'chk1', Name: 'serfHealth', Status: 'passing');
        self::assertSame('node1', $c->getNode());
        self::assertSame('chk1', $c->getCheckID());
        self::assertSame('serfHealth', $c->getName());
        self::assertSame('passing', $c->getStatus());
    }

    public function testFluentSetters(): void
    {
        $c = new AgentCheck();
        $result = $c->setNode('n')->setCheckID('c')->setName('nm')
            ->setStatus('critical')->setNotes('note')->setOutput('out')
            ->setServiceID('svc')->setServiceName('svcn')->setType('http')
            ->setNamespace('ns');
        self::assertSame($c, $result);
        self::assertSame('n', $c->getNode());
        self::assertSame('critical', $c->getStatus());
    }

    public function testJsonSerialize(): void
    {
        $c = new AgentCheck(Node: 'n', CheckID: 'c');
        $out = $c->jsonSerialize();
        self::assertSame('n', $out->Node);
        self::assertSame('c', $out->CheckID);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Node = 'n1';
        $d->CheckID = 'chk1';
        $d->Name = 'test';
        $d->Status = 'passing';
        $d->Notes = '';
        $d->Output = '';
        $d->ServiceID = '';
        $d->ServiceName = '';
        $d->Type = '';
        $d->Definition = new \stdClass();
        $c = AgentCheck::jsonUnserialize($d);
        self::assertSame('n1', $c->getNode());
        self::assertSame('chk1', $c->getCheckID());
        self::assertInstanceOf(HealthCheckDefinition::class, $c->getDefinition());
    }

    public function testToString(): void
    {
        $c = new AgentCheck(CheckID: 'my-check');
        self::assertSame('my-check', (string)$c);
    }
}

