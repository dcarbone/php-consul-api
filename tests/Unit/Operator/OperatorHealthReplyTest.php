<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\OperatorHealthReply;
use DCarbone\PHPConsulAPI\Operator\ServerHealth;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class OperatorHealthReplyTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new OperatorHealthReply();
        self::assertFalse($r->isHealthy());
        self::assertFalse($r->Healthy);
        self::assertSame(0, $r->getFailureTolerance());
        self::assertSame(0, $r->FailureTolerance);
        self::assertSame([], $r->getServers());
        self::assertSame([], $r->Servers);
    }

    public function testConstructorWithValues(): void
    {
        $sh = new ServerHealth(ID: 'srv-1', Healthy: true);
        $r = new OperatorHealthReply(Healthy: true, FailureTolerance: 1, Servers: [$sh]);
        self::assertTrue($r->isHealthy());
        self::assertTrue($r->Healthy);
        self::assertSame(1, $r->getFailureTolerance());
        self::assertSame(1, $r->FailureTolerance);
        self::assertCount(1, $r->getServers());
        self::assertCount(1, $r->Servers);
        self::assertSame('srv-1', $r->getServers()[0]->getID());
    }

    public function testFluentSetters(): void
    {
        $r = new OperatorHealthReply();
        $result = $r
            ->setHealthy(true)
            ->setFailureTolerance(2)
            ->setServers(new ServerHealth(ID: 'a'), new ServerHealth(ID: 'b'));
        self::assertSame($r, $result);
        self::assertTrue($r->Healthy);
        self::assertSame(2, $r->FailureTolerance);
        self::assertCount(2, $r->getServers());
        self::assertCount(2, $r->Servers);
    }

    public function testJsonSerialize(): void
    {
        $r = new OperatorHealthReply(Healthy: true, FailureTolerance: 1);
        $out = $r->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertTrue($out->Healthy);
        self::assertSame(1, $out->FailureTolerance);
    }

    public function testJsonUnserialize(): void
    {
        $shObj = new \stdClass();
        $shObj->ID = 'sh1';
        $shObj->Name = 'node';
        $shObj->Address = '';
        $shObj->SerfStatus = '';
        $shObj->Version = '';
        $shObj->Leader = false;
        $shObj->LastTerm = 0;
        $shObj->LastIndex = 0;
        $shObj->Healthy = true;
        $shObj->Voter = true;
        $shObj->StableSince = '2024-01-01T00:00:00Z';

        $decoded = new \stdClass();
        $decoded->Healthy = true;
        $decoded->FailureTolerance = 1;
        $decoded->Servers = [$shObj];

        $r = OperatorHealthReply::jsonUnserialize($decoded);
        self::assertTrue($r->isHealthy());
        self::assertSame(1, $r->getFailureTolerance());
        self::assertCount(1, $r->getServers());
        self::assertInstanceOf(ServerHealth::class, $r->getServers()[0]);
    }
}

