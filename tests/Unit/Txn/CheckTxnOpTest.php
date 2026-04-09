<?php

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Txn\CheckOp;
use DCarbone\PHPConsulAPI\Txn\CheckTxnOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CheckTxnOpTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $op = new CheckTxnOp();
        self::assertSame(CheckOp::UNDEFINED, $op->getVerb());
        self::assertSame(CheckOp::UNDEFINED, $op->Verb);
        self::assertInstanceOf(HealthCheck::class, $op->getCheck());
        self::assertInstanceOf(HealthCheck::class, $op->Check);
    }

    public function testConstructorWithEnumVerb(): void
    {
        $check = new HealthCheck(Node: 'node-1', Name: 'my-check');
        $op = new CheckTxnOp(Verb: CheckOp::CheckGet, Check: $check);
        self::assertSame(CheckOp::CheckGet, $op->getVerb());
        self::assertSame('node-1', $op->getCheck()->getNode());
        self::assertSame('my-check', $op->getCheck()->getName());
    }

    public function testConstructorWithStringVerb(): void
    {
        $op = new CheckTxnOp(Verb: 'set');
        self::assertSame(CheckOp::CheckSet, $op->getVerb());
    }

    public function testConstructorWithInvalidStringVerbThrows(): void
    {
        $this->expectException(\ValueError::class);
        new CheckTxnOp(Verb: 'not-valid');
    }

    public function testSetVerbWithString(): void
    {
        $op = new CheckTxnOp();
        $result = $op->setVerb('cas');
        self::assertSame($op, $result);
        self::assertSame(CheckOp::CheckCAS, $op->getVerb());
    }

    public function testSetVerbWithEnum(): void
    {
        $op = new CheckTxnOp();
        $op->setVerb(CheckOp::CheckDelete);
        self::assertSame(CheckOp::CheckDelete, $op->getVerb());
    }

    public function testFluentSetters(): void
    {
        $op = new CheckTxnOp();
        $check = new HealthCheck(Name: 'test');
        $result = $op
            ->setVerb(CheckOp::CheckSet)
            ->setCheck($check);
        self::assertSame($op, $result);
        self::assertSame(CheckOp::CheckSet, $op->getVerb());
        self::assertSame('test', $op->getCheck()->getName());
    }

    public function testJsonSerialize(): void
    {
        $op = new CheckTxnOp(Verb: CheckOp::CheckGet, Check: new HealthCheck(Name: 'hc'));
        $out = $op->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(CheckOp::CheckGet, $out->Verb);
    }

    public function testJsonUnserialize(): void
    {
        $checkObj = new \stdClass();
        $checkObj->Node = 'n1';
        $checkObj->CheckID = 'c1';
        $checkObj->Name = 'check1';
        $checkObj->Status = 'passing';
        $checkObj->Notes = '';
        $checkObj->Output = '';
        $checkObj->ServiceID = '';
        $checkObj->ServiceName = '';
        $checkObj->ServiceTags = [];
        $checkObj->Type = '';
        $checkObj->Namespace = '';
        $checkObj->Partition = '';
        $checkObj->ExposedPort = 0;
        $checkObj->PeerName = '';
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
        $checkObj->Definition = $defObj;
        $checkObj->CreateIndex = 1;
        $checkObj->ModifyIndex = 2;

        $decoded = new \stdClass();
        $decoded->Verb = 'get';
        $decoded->Check = $checkObj;

        $op = CheckTxnOp::jsonUnserialize($decoded);
        self::assertSame(CheckOp::CheckGet, $op->getVerb());
        self::assertSame('n1', $op->getCheck()->getNode());
        self::assertSame('check1', $op->getCheck()->getName());
    }

    public function testJsonRoundTrip(): void
    {
        $op = new CheckTxnOp(Verb: CheckOp::CheckCAS, Check: new HealthCheck(Name: 'rt'));
        $decoded = json_decode(json_encode($op));
        $restored = CheckTxnOp::jsonUnserialize($decoded);
        self::assertSame(CheckOp::CheckCAS, $restored->getVerb());
        self::assertSame('rt', $restored->getCheck()->getName());
    }
}

