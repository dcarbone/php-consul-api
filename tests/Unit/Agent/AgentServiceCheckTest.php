<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentServiceCheck;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServiceCheckTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new AgentServiceCheck();
        self::assertSame('', $c->getCheckID());
        self::assertSame('', $c->getName());
        self::assertSame([], $c->getArgs());
        self::assertSame('', $c->getDockerContainerID());
        self::assertSame('', $c->getInterval());
        self::assertSame('', $c->getTimeout());
        self::assertSame('', $c->getTTL());
        self::assertSame('', $c->getHTTP());
        self::assertSame('', $c->getMethod());
        self::assertSame('', $c->getTCP());
        self::assertSame('', $c->getStatus());
        self::assertSame('', $c->getNotes());
        self::assertFalse($c->isTLSSkipVerify());
        self::assertSame('', $c->getGRPC());
        self::assertFalse($c->isGRPCUseTLS());
        self::assertSame(0, $c->getSuccessBeforePassing());
        self::assertSame(0, $c->getFailuresBeforeCritical());
        self::assertSame('', $c->getDeregisterCriticalServiceAfter());
    }

    public function testConstructorWithParams(): void
    {
        $c = new AgentServiceCheck(
            CheckID: 'chk-1',
            Name: 'http-check',
            HTTP: 'http://localhost:8080/health',
            Interval: '10s',
            Timeout: '5s',
            Status: 'critical',
        );
        self::assertSame('chk-1', $c->getCheckID());
        self::assertSame('http-check', $c->getName());
        self::assertSame('http://localhost:8080/health', $c->getHTTP());
        self::assertSame('10s', $c->getInterval());
        self::assertSame('5s', $c->getTimeout());
        self::assertSame('critical', $c->getStatus());
    }

    public function testFluentSetters(): void
    {
        $c = new AgentServiceCheck();
        $result = $c->setCheckID('c')->setName('n')->setHTTP('http://example.com')
            ->setInterval('5s')->setTimeout('2s')->setStatus('passing');
        self::assertSame($c, $result);
        self::assertSame('c', $c->getCheckID());
        self::assertSame('http://example.com', $c->getHTTP());
    }

    public function testJsonSerialize(): void
    {
        $c = new AgentServiceCheck(CheckID: 'chk', Name: 'test', TTL: '30s');
        $out = $c->jsonSerialize();
        self::assertSame('chk', $out->CheckID);
        self::assertSame('test', $out->Name);
        self::assertSame('30s', $out->TTL);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->CheckID = 'c1';
        $d->Name = 'check';
        $d->HTTP = 'http://localhost';
        $d->Interval = '10s';
        $c = AgentServiceCheck::jsonUnserialize($d);
        self::assertSame('c1', $c->getCheckID());
        self::assertSame('http://localhost', $c->getHTTP());
    }
}

