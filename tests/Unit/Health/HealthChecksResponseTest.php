<?php

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Health\HealthChecksResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HealthChecksResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new HealthChecksResponse();
        self::assertInstanceOf(HealthChecks::class, $r->HealthChecks);
        self::assertInstanceOf(HealthChecks::class, $r->getValue());
        self::assertCount(0, $r->HealthChecks);
        self::assertCount(0, $r->getValue());
    }

    public function testUnmarshalValue(): void
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
        $d1->Definition = clone $defObj;
        $d1->CreateIndex = 1;
        $d1->ModifyIndex = 2;

        $d2 = new \stdClass();
        $d2->Node = 'n2';
        $d2->CheckID = 'c2';
        $d2->Name = 'check2';
        $d2->Status = 'critical';
        $d2->Notes = '';
        $d2->Output = '';
        $d2->ServiceID = '';
        $d2->ServiceName = '';
        $d2->ServiceTags = [];
        $d2->Type = '';
        $d2->Namespace = '';
        $d2->Partition = '';
        $d2->ExposedPort = 0;
        $d2->PeerName = '';
        $d2->Definition = clone $defObj;
        $d2->CreateIndex = 3;
        $d2->ModifyIndex = 4;

        $r = new HealthChecksResponse();
        $r->unmarshalValue([$d1, $d2]);

        self::assertCount(2, $r->HealthChecks);
        self::assertCount(2, $r->getValue());
        self::assertInstanceOf(HealthCheck::class, $r->HealthChecks[0]);
        self::assertInstanceOf(HealthCheck::class, $r->HealthChecks[1]);
        self::assertSame('n1', $r->HealthChecks[0]->Node);
        self::assertSame('check1', $r->HealthChecks[0]->Name);
        self::assertSame('passing', $r->HealthChecks[0]->Status);
        self::assertSame('n2', $r->HealthChecks[1]->Node);
        self::assertSame('check2', $r->HealthChecks[1]->Name);
        self::assertSame('critical', $r->HealthChecks[1]->Status);
    }

    public function testUnmarshalValueWithNull(): void
    {
        $r = new HealthChecksResponse();
        $r->unmarshalValue(null);
        self::assertInstanceOf(HealthChecks::class, $r->HealthChecks);
        self::assertCount(0, $r->HealthChecks);
    }

    public function testUnmarshalValueResetsChecks(): void
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

        $d = new \stdClass();
        $d->Node = 'n';
        $d->CheckID = 'c';
        $d->Name = 'check';
        $d->Status = 'passing';
        $d->Notes = '';
        $d->Output = '';
        $d->ServiceID = '';
        $d->ServiceName = '';
        $d->ServiceTags = [];
        $d->Type = '';
        $d->Namespace = '';
        $d->Partition = '';
        $d->ExposedPort = 0;
        $d->PeerName = '';
        $d->Definition = $defObj;
        $d->CreateIndex = 0;
        $d->ModifyIndex = 0;

        $r = new HealthChecksResponse();
        $r->unmarshalValue([$d]);
        self::assertCount(1, $r->HealthChecks);

        $r->unmarshalValue(null);
        self::assertCount(0, $r->HealthChecks);
    }
}

