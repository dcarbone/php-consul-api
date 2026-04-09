<?php

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HealthChecksTest extends TestCase
{
    public function testConstructorEmpty(): void
    {
        $hcs = new HealthChecks();
        self::assertCount(0, $hcs);
    }

    public function testConstructorWithChecks(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Name: 'c1', Status: 'passing'),
            new HealthCheck(Name: 'c2', Status: 'critical'),
        );
        self::assertCount(2, $hcs);
        self::assertSame('c1', $hcs[0]->Name);
        self::assertSame('c2', $hcs[1]->Name);
    }

    public function testCountable(): void
    {
        $hcs = new HealthChecks(new HealthCheck());
        self::assertSame(1, $hcs->count());
    }

    public function testIteratorAggregate(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Name: 'a'),
            new HealthCheck(Name: 'b'),
        );
        $names = [];
        foreach ($hcs as $hc) {
            $names[] = $hc->getName();
        }
        self::assertSame(['a', 'b'], $names);
    }

    public function testArrayAccessOffsetExists(): void
    {
        $hcs = new HealthChecks(new HealthCheck(Name: 'x'));
        self::assertTrue(isset($hcs[0]));
        self::assertFalse(isset($hcs[1]));
    }

    public function testArrayAccessOffsetGet(): void
    {
        $hcs = new HealthChecks(new HealthCheck(Name: 'x'));
        self::assertSame('x', $hcs[0]->getName());
        self::assertSame('x', $hcs[0]->Name);
    }

    public function testArrayAccessOffsetGetThrowsOutOfRange(): void
    {
        $hcs = new HealthChecks();
        $this->expectException(\OutOfRangeException::class);
        $_ = $hcs[0];
    }

    public function testArrayAccessOffsetSet(): void
    {
        $hcs = new HealthChecks();
        $hcs[] = new HealthCheck(Name: 'appended');
        self::assertCount(1, $hcs);
        self::assertSame('appended', $hcs[0]->Name);
    }

    public function testArrayAccessOffsetSetAtIndex(): void
    {
        $hcs = new HealthChecks(new HealthCheck(Name: 'old'));
        $hcs[0] = new HealthCheck(Name: 'new');
        self::assertCount(1, $hcs);
        self::assertSame('new', $hcs[0]->Name);
    }

    public function testArrayAccessOffsetSetThrowsOnInvalidValue(): void
    {
        $hcs = new HealthChecks();
        $this->expectException(\InvalidArgumentException::class);
        $hcs[] = 'not a health check'; // @phpstan-ignore offsetAssign.valueType
    }

    public function testArrayAccessOffsetSetThrowsOnInvalidOffset(): void
    {
        $hcs = new HealthChecks();
        $this->expectException(\InvalidArgumentException::class);
        $hcs['bad'] = new HealthCheck(); // @phpstan-ignore offsetAssign.dimType
    }

    public function testArrayAccessOffsetUnset(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Name: 'a'),
            new HealthCheck(Name: 'b'),
        );
        unset($hcs[0]);
        self::assertCount(1, $hcs);
    }

    public function testAggregatedStatusAllPassing(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Status: Consul::HealthPassing),
            new HealthCheck(Status: Consul::HealthPassing),
        );
        self::assertSame(Consul::HealthPassing, $hcs->AggregatedStatus());
    }

    public function testAggregatedStatusCritical(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Status: Consul::HealthPassing),
            new HealthCheck(Status: Consul::HealthCritical),
        );
        self::assertSame(Consul::HealthCritical, $hcs->AggregatedStatus());
    }

    public function testAggregatedStatusWarning(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Status: Consul::HealthPassing),
            new HealthCheck(Status: Consul::HealthWarning),
        );
        self::assertSame(Consul::HealthWarning, $hcs->AggregatedStatus());
    }

    public function testAggregatedStatusMaintenance(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(CheckID: Consul::NodeMaint, Status: Consul::HealthCritical),
            new HealthCheck(Status: Consul::HealthPassing),
        );
        self::assertSame(Consul::HealthMaint, $hcs->AggregatedStatus());
    }

    public function testAggregatedStatusServiceMaintenance(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(CheckID: Consul::ServiceMaintPrefix . 'web', Status: Consul::HealthCritical),
            new HealthCheck(Status: Consul::HealthPassing),
        );
        self::assertSame(Consul::HealthMaint, $hcs->AggregatedStatus());
    }

    public function testAggregatedStatusEmpty(): void
    {
        $hcs = new HealthChecks();
        self::assertSame(Consul::HealthPassing, $hcs->AggregatedStatus());
    }

    public function testAggregatedStatusUnknownReturnsEmpty(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Status: 'something-unknown'),
        );
        self::assertSame('', $hcs->AggregatedStatus());
    }

    public function testJsonSerialize(): void
    {
        $hcs = new HealthChecks(new HealthCheck(Name: 'test'));
        $out = $hcs->jsonSerialize();
        self::assertCount(1, $out);
        self::assertInstanceOf(HealthCheck::class, $out[0]);
    }

    public function testJsonUnserialize(): void
    {
        $d1 = new \stdClass();
        $d1->Node = 'n1';
        $d1->CheckID = 'c1';
        $d1->Name = 'check1';
        $d1->Status = 'passing';
        $d1->Notes = '';
        $d1->Output = '';
        $d1->ServiceID = '';
        $d1->ServiceName = '';
        $d1->ServiceTags = [];
        $d1->Type = '';
        $d1->Namespace = '';
        $d1->Partition = '';
        $d1->ExposedPort = 0;
        $d1->PeerName = '';
        $d1->Definition = new \stdClass();
        $d1->Definition->HTTP = '';
        $d1->Definition->Method = '';
        $d1->Definition->Body = '';
        $d1->Definition->TLSSkipVerify = false;
        $d1->Definition->TCP = '';
        $d1->Definition->TCPUseTLS = false;
        $d1->Definition->UDP = '';
        $d1->Definition->GRPC = '';
        $d1->Definition->OSService = '';
        $d1->Definition->GRPCUseTLS = false;
        $d1->Definition->IntervalDuration = 0;
        $d1->Definition->TimeoutDuration = 0;
        $d1->Definition->DeregisterCriticalServiceAfterDuration = 0;
        $d1->CreateIndex = 1;
        $d1->ModifyIndex = 2;

        $d2 = clone $d1;
        $d2->Node = 'n2';
        $d2->CheckID = 'c2';
        $d2->Name = 'check2';
        $d2->Status = 'critical';
        $d2->Definition = clone $d1->Definition;

        $hcs = HealthChecks::jsonUnserialize([$d1, $d2]);
        self::assertCount(2, $hcs);
        self::assertSame('n1', $hcs[0]->Node);
        self::assertSame('check1', $hcs[0]->Name);
        self::assertSame('n2', $hcs[1]->Node);
        self::assertSame('check2', $hcs[1]->Name);
        self::assertSame('critical', $hcs[1]->Status);
    }

    public function testJsonUnserializeEmpty(): void
    {
        $hcs = HealthChecks::jsonUnserialize([]);
        self::assertCount(0, $hcs);
    }
}
