<?php

namespace DCarbone\PHPConsulAPITests\Unit\Peering;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\Peering\Peering;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PeeringTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new Peering();
        self::assertTrue($p->CreateIndex->isZero());
        self::assertTrue($p->ModifyIndex->isZero());
    }

    public function testConstructorAcceptsIntStringAndBigInteger(): void
    {
        $p = new Peering(Name: 'p1', CreateIndex: 5, ModifyIndex: '18446744073709551615');
        self::assertSame('p1', $p->Name);
        self::assertSame('5', (string)$p->CreateIndex);
        self::assertSame('18446744073709551615', (string)$p->ModifyIndex);

        $big = BigInteger::of('9223372036854775808');
        $p = new Peering(CreateIndex: $big, ModifyIndex: $big);
        self::assertTrue($p->CreateIndex->isEqualTo($big));
        self::assertTrue($p->ModifyIndex->isEqualTo($big));
    }

    public function testConstructorRejectsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Peering(CreateIndex: -1);
    }

    public function testJsonUnserializeLargeIndexes(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'id-1';
        $decoded->Name = 'p1';
        $decoded->CreateIndex = '18446744073709551615';
        $decoded->ModifyIndex = 10;
        $p = Peering::jsonUnserialize($decoded);
        self::assertSame('id-1', $p->ID);
        self::assertSame('18446744073709551615', (string)$p->CreateIndex);
        self::assertSame('10', (string)$p->ModifyIndex);
    }

    public function testJsonSerialize(): void
    {
        $p = new Peering(Name: 'p1', CreateIndex: '18446744073709551615', ModifyIndex: 3);
        $out = $p->jsonSerialize();
        self::assertInstanceOf(BigInteger::class, $out->CreateIndex);
        self::assertSame('18446744073709551615', (string)$out->CreateIndex);
        self::assertSame('3', (string)$out->ModifyIndex);
    }
}
