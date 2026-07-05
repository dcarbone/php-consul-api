<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\TxnError;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TxnErrorTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $err = new TxnError();
        self::assertSame(0, $err->getOpIndex());
        self::assertSame('', $err->getWhat());
    }

    public function testConstructorWithValues(): void
    {
        $err = new TxnError(OpIndex: 5, What: 'permission denied');
        self::assertSame(5, $err->getOpIndex());
        self::assertSame('permission denied', $err->getWhat());
    }

    public function testFluentSetters(): void
    {
        $err = new TxnError();
        $result = $err
            ->setOpIndex(3)
            ->setWhat('timeout');

        self::assertSame($err, $result);
        self::assertSame(3, $err->getOpIndex());
        self::assertSame('timeout', $err->getWhat());
    }

    public function testJsonSerialize(): void
    {
        $err = new TxnError(OpIndex: 7, What: 'key not found');
        $out = $err->jsonSerialize();

        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(7, $out->OpIndex);
        self::assertSame('key not found', $out->What);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->OpIndex = 4;
        $decoded->What = 'cas mismatch';

        $err = TxnError::jsonUnserialize($decoded);

        self::assertSame(4, $err->getOpIndex());
        self::assertSame('cas mismatch', $err->getWhat());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new TxnError(OpIndex: 9, What: 'round trip error');

        $restored = TxnError::jsonUnserialize($original->jsonSerialize());

        self::assertSame($original->getOpIndex(), $restored->getOpIndex());
        self::assertSame($original->getWhat(), $restored->getWhat());
    }
}

