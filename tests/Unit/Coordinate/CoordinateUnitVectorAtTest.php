<?php

namespace DCarbone\PHPConsulAPITests\Unit\Coordinate;

use DCarbone\PHPConsulAPI\Coordinate\CoordinateUnitVectorAt;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CoordinateUnitVectorAtTest extends TestCase
{
    public function testConstructor(): void
    {
        $uva = new CoordinateUnitVectorAt(vec: [1.0, 0.0], mag: 5.0);
        self::assertSame([1.0, 0.0], $uva->vec);
        self::assertSame(5.0, $uva->mag);
    }

    public function testOffsetExistsValidIndices(): void
    {
        $uva = new CoordinateUnitVectorAt(vec: [1.0], mag: 2.0);
        self::assertTrue($uva->offsetExists(0));
        self::assertTrue($uva->offsetExists(1));
        self::assertFalse($uva->offsetExists(2));
        self::assertFalse($uva->offsetExists(-1));
    }

    public function testOffsetGetVec(): void
    {
        $uva = new CoordinateUnitVectorAt(vec: [3.0, 4.0], mag: 5.0);
        self::assertSame([3.0, 4.0], $uva[0]);
    }

    public function testOffsetGetMag(): void
    {
        $uva = new CoordinateUnitVectorAt(vec: [3.0, 4.0], mag: 5.0);
        self::assertSame(5.0, $uva[1]);
    }

    public function testOffsetGetInvalidThrows(): void
    {
        $uva = new CoordinateUnitVectorAt(vec: [1.0], mag: 1.0);
        $this->expectException(\OutOfBoundsException::class);
        $uva[2];
    }

    public function testOffsetSetThrows(): void
    {
        $uva = new CoordinateUnitVectorAt(vec: [1.0], mag: 1.0);
        $this->expectException(\BadMethodCallException::class);
        $uva[0] = [2.0];
    }

    public function testOffsetUnsetThrows(): void
    {
        $uva = new CoordinateUnitVectorAt(vec: [1.0], mag: 1.0);
        $this->expectException(\BadMethodCallException::class);
        unset($uva[0]);
    }
}

