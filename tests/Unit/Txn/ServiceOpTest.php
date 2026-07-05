<?php

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\ServiceOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceOpTest extends TestCase
{
    public function testServiceGetValue(): void
    {
        self::assertSame('get', ServiceOp::ServiceGet->value);
    }

    public function testServiceSetValue(): void
    {
        self::assertSame('set', ServiceOp::ServiceSet->value);
    }

    public function testServiceCASValue(): void
    {
        self::assertSame('cas', ServiceOp::ServiceCAS->value);
    }

    public function testServiceDeleteValue(): void
    {
        self::assertSame('delete', ServiceOp::ServiceDelete->value);
    }

    public function testServiceDeleteCASValue(): void
    {
        self::assertSame('delete-cas', ServiceOp::ServiceDeleteCAS->value);
    }

    public function testUndefinedValue(): void
    {
        self::assertSame('', ServiceOp::UNDEFINED->value);
    }

    public function testFromValidString(): void
    {
        self::assertSame(ServiceOp::ServiceGet, ServiceOp::from('get'));
        self::assertSame(ServiceOp::ServiceSet, ServiceOp::from('set'));
        self::assertSame(ServiceOp::ServiceCAS, ServiceOp::from('cas'));
        self::assertSame(ServiceOp::ServiceDelete, ServiceOp::from('delete'));
        self::assertSame(ServiceOp::ServiceDeleteCAS, ServiceOp::from('delete-cas'));
        self::assertSame(ServiceOp::UNDEFINED, ServiceOp::from(''));
    }

    public function testFromInvalidStringThrows(): void
    {
        $this->expectException(\ValueError::class);
        ServiceOp::from('not-valid');
    }

    public function testTryFromValidString(): void
    {
        self::assertSame(ServiceOp::ServiceGet, ServiceOp::tryFrom('get'));
    }

    public function testTryFromInvalidStringReturnsNull(): void
    {
        self::assertNull(ServiceOp::tryFrom('not-valid'));
    }

    public function testCasesReturnsAllValues(): void
    {
        $cases = ServiceOp::cases();
        self::assertCount(6, $cases);
    }
}

