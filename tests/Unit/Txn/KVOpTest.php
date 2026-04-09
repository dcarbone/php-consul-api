<?php

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\KVOp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KVOpTest extends TestCase
{
    public function testKVSetValue(): void
    {
        self::assertSame('set', KVOp::KVSet->value);
    }

    public function testKVDeleteValue(): void
    {
        self::assertSame('delete', KVOp::KVDelete->value);
    }

    public function testKVDeleteCASValue(): void
    {
        self::assertSame('delete-cas', KVOp::KVDeleteCAS->value);
    }

    public function testKVDeleteTreeValue(): void
    {
        self::assertSame('delete-tree', KVOp::KVDeleteTree->value);
    }

    public function testKVCASValue(): void
    {
        self::assertSame('cas', KVOp::KVCAS->value);
    }

    public function testKVLockValue(): void
    {
        self::assertSame('lock', KVOp::KVLock->value);
    }

    public function testKVUnlockValue(): void
    {
        self::assertSame('unlock', KVOp::KVUnlock->value);
    }

    public function testKVGetValue(): void
    {
        self::assertSame('get', KVOp::KVGet->value);
    }

    public function testKVGetOrEmptyValue(): void
    {
        self::assertSame('get-or-empty', KVOp::KVGetOrEmpty->value);
    }

    public function testKVGetTreeValue(): void
    {
        self::assertSame('get-tree', KVOp::KVGetTree->value);
    }

    public function testKVCheckSessionValue(): void
    {
        self::assertSame('check-session', KVOp::KVCheckSession->value);
    }

    public function testKVCheckIndexValue(): void
    {
        self::assertSame('check-index', KVOp::KVCheckIndex->value);
    }

    public function testKVCheckNotExistsValue(): void
    {
        self::assertSame('check-not-exists', KVOp::KVCheckNotExists->value);
    }

    public function testUndefinedValue(): void
    {
        self::assertSame('', KVOp::UNDEFINED->value);
    }

    public function testFromValidString(): void
    {
        self::assertSame(KVOp::KVSet, KVOp::from('set'));
        self::assertSame(KVOp::KVDelete, KVOp::from('delete'));
        self::assertSame(KVOp::KVDeleteCAS, KVOp::from('delete-cas'));
        self::assertSame(KVOp::KVDeleteTree, KVOp::from('delete-tree'));
        self::assertSame(KVOp::KVCAS, KVOp::from('cas'));
        self::assertSame(KVOp::KVLock, KVOp::from('lock'));
        self::assertSame(KVOp::KVUnlock, KVOp::from('unlock'));
        self::assertSame(KVOp::KVGet, KVOp::from('get'));
        self::assertSame(KVOp::KVGetOrEmpty, KVOp::from('get-or-empty'));
        self::assertSame(KVOp::KVGetTree, KVOp::from('get-tree'));
        self::assertSame(KVOp::KVCheckSession, KVOp::from('check-session'));
        self::assertSame(KVOp::KVCheckIndex, KVOp::from('check-index'));
        self::assertSame(KVOp::KVCheckNotExists, KVOp::from('check-not-exists'));
        self::assertSame(KVOp::UNDEFINED, KVOp::from(''));
    }

    public function testFromInvalidStringThrows(): void
    {
        $this->expectException(\ValueError::class);
        KVOp::from('not-valid');
    }

    public function testTryFromValidString(): void
    {
        self::assertSame(KVOp::KVSet, KVOp::tryFrom('set'));
    }

    public function testTryFromInvalidStringReturnsNull(): void
    {
        self::assertNull(KVOp::tryFrom('not-valid'));
    }

    public function testCasesReturnsAllValues(): void
    {
        $cases = KVOp::cases();
        self::assertCount(14, $cases);
    }
}

