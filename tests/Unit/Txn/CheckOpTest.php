<?php

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\CheckOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CheckOpTest extends TestCase
{
    public function testCheckGetValue(): void
    {
        self::assertSame('get', CheckOp::CheckGet->value);
    }

    public function testCheckSetValue(): void
    {
        self::assertSame('set', CheckOp::CheckSet->value);
    }

    public function testCheckCASValue(): void
    {
        self::assertSame('cas', CheckOp::CheckCAS->value);
    }

    public function testCheckDeleteValue(): void
    {
        self::assertSame('delete', CheckOp::CheckDelete->value);
    }

    public function testCheckDeleteCASValue(): void
    {
        self::assertSame('delete-cas', CheckOp::CheckDeleteCAS->value);
    }

    public function testUndefinedValue(): void
    {
        self::assertSame('', CheckOp::UNDEFINED->value);
    }

    public function testFromValidString(): void
    {
        self::assertSame(CheckOp::CheckGet, CheckOp::from('get'));
        self::assertSame(CheckOp::CheckSet, CheckOp::from('set'));
        self::assertSame(CheckOp::CheckCAS, CheckOp::from('cas'));
        self::assertSame(CheckOp::CheckDelete, CheckOp::from('delete'));
        self::assertSame(CheckOp::CheckDeleteCAS, CheckOp::from('delete-cas'));
        self::assertSame(CheckOp::UNDEFINED, CheckOp::from(''));
    }

    public function testFromInvalidStringThrows(): void
    {
        $this->expectException(\ValueError::class);
        CheckOp::from('not-valid');
    }

    public function testTryFromValidString(): void
    {
        self::assertSame(CheckOp::CheckGet, CheckOp::tryFrom('get'));
    }

    public function testTryFromInvalidStringReturnsNull(): void
    {
        self::assertNull(CheckOp::tryFrom('not-valid'));
    }

    public function testCasesReturnsAllValues(): void
    {
        $cases = CheckOp::cases();
        self::assertCount(6, $cases);
    }
}

