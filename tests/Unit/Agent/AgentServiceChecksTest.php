<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentServiceCheck;
use DCarbone\PHPConsulAPI\Agent\AgentServiceChecks;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServiceChecksTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new AgentServiceChecks();
        self::assertSame([], $c->getChecks());
        self::assertCount(0, $c);
    }

    public function testConstructorWithParams(): void
    {
        $chk = new AgentServiceCheck(CheckID: 'c1', Name: 'test');
        $c = new AgentServiceChecks(Checks: [$chk]);
        self::assertCount(1, $c);
        self::assertSame($chk, $c->getChecks()[0]);
    }

    public function testSetChecksVariadic(): void
    {
        $c = new AgentServiceChecks();
        $chk1 = new AgentServiceCheck(CheckID: 'c1');
        $chk2 = new AgentServiceCheck(CheckID: 'c2');
        $result = $c->setChecks($chk1, $chk2);
        self::assertSame($c, $result);
        self::assertCount(2, $c);
        self::assertSame($chk1, $c->getChecks()[0]);
        self::assertSame($chk2, $c->getChecks()[1]);
    }

    public function testArrayAccessOffsetExists(): void
    {
        $chk = new AgentServiceCheck(CheckID: 'c1');
        $c = new AgentServiceChecks(Checks: [$chk]);
        self::assertTrue(isset($c[0]));
        self::assertFalse(isset($c[1]));
    }

    public function testArrayAccessOffsetGet(): void
    {
        $chk = new AgentServiceCheck(CheckID: 'c1');
        $c = new AgentServiceChecks(Checks: [$chk]);
        self::assertSame($chk, $c[0]);
        self::assertNull($c[99]);
    }

    public function testArrayAccessOffsetSet(): void
    {
        $c = new AgentServiceChecks();
        $chk = new AgentServiceCheck(CheckID: 'c1');
        $c->setChecks($chk);
        $chk2 = new AgentServiceCheck(CheckID: 'c2');
        $c[0] = $chk2;
        self::assertSame($chk2, $c[0]);
    }

    public function testArrayAccessOffsetUnset(): void
    {
        $chk = new AgentServiceCheck(CheckID: 'c1');
        $c = new AgentServiceChecks(Checks: [$chk]);
        unset($c[0]);
        self::assertFalse(isset($c[0]));
    }

    public function testCountable(): void
    {
        $c = new AgentServiceChecks(Checks: [
            new AgentServiceCheck(CheckID: 'c1'),
            new AgentServiceCheck(CheckID: 'c2'),
        ]);
        self::assertSame(2, $c->count());
        self::assertCount(2, $c);
    }

    public function testJsonSerialize(): void
    {
        $chk = new AgentServiceCheck(CheckID: 'c1', HTTP: 'http://localhost');
        $c = new AgentServiceChecks(Checks: [$chk]);
        $out = $c->jsonSerialize();
        self::assertCount(1, $out);
        self::assertInstanceOf(AgentServiceCheck::class, $out[0]);
    }

    public function testJsonUnserialize(): void
    {
        $obj = new \stdClass();
        $obj->CheckID = 'c1';
        $obj->Name = 'test';
        $obj->Args = [];
        $obj->DockerContainerID = '';
        $obj->Shell = '';
        $obj->Interval = '10s';
        $obj->Timeout = '';
        $obj->TTL = '';
        $obj->HTTP = '';
        $obj->Method = '';
        $obj->TCP = '';
        $obj->Status = '';
        $obj->Notes = '';
        $obj->TLSSkipVerify = false;
        $obj->GRPC = '';
        $obj->GRPCUseTLS = false;
        $obj->H2PING = '';
        $obj->H2PINGUseTLS = false;
        $obj->AliasNode = '';
        $obj->AliasService = '';
        $obj->SuccessBeforePassing = 0;
        $obj->FailuresBeforeCritical = 0;
        $obj->DeregisterCriticalServiceAfter = '';

        $c = AgentServiceChecks::jsonUnserialize([$obj]);
        self::assertInstanceOf(AgentServiceChecks::class, $c);
        self::assertCount(1, $c);
        self::assertInstanceOf(AgentServiceCheck::class, $c[0]);
        self::assertSame('c1', $c[0]->getCheckID());
    }
}

