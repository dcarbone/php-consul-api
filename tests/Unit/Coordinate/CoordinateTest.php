<?php

namespace DCarbone\PHPConsulAPITests\Unit\Coordinate;

use DCarbone\PHPConsulAPI\Coordinate\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateConfig;
use DCarbone\PHPConsulAPI\Coordinate\DimensionalityConflictException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CoordinateTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new Coordinate();
        self::assertSame([], $c->getVec());
        self::assertSame([], $c->Vec);
        self::assertSame(0.0, $c->getError());
        self::assertSame(0.0, $c->Error);
        self::assertSame(0.0, $c->getAdjustment());
        self::assertSame(0.0, $c->Adjustment);
        self::assertSame(0.0, $c->getHeight());
        self::assertSame(0.0, $c->Height);
    }

    public function testConstructorWithValues(): void
    {
        $c = new Coordinate(Vec: [1.0, 2.0, 3.0], Error: 0.5, Adjustment: 0.1, Height: 0.01);
        self::assertSame([1.0, 2.0, 3.0], $c->getVec());
        self::assertSame([1.0, 2.0, 3.0], $c->Vec);
        self::assertSame(0.5, $c->getError());
        self::assertSame(0.5, $c->Error);
        self::assertSame(0.1, $c->getAdjustment());
        self::assertSame(0.1, $c->Adjustment);
        self::assertSame(0.01, $c->getHeight());
        self::assertSame(0.01, $c->Height);
    }

    public function testConstructorWithConfig(): void
    {
        $config = new CoordinateConfig(Dimensionality: 4);
        $c = new Coordinate(config: $config);
        self::assertCount(4, $c->Vec);
        self::assertSame([0.0, 0.0, 0.0, 0.0], $c->Vec);
        self::assertSame($config->VivaldiErrorMax, $c->Error);
        self::assertSame(0.0, $c->Adjustment);
        self::assertSame($config->HeightMin, $c->Height);
    }

    public function testSettersWithDirectFieldAccess(): void
    {
        $c = new Coordinate();

        $c->setVec(1.0, 2.0);
        self::assertSame([1.0, 2.0], $c->getVec());
        self::assertSame([1.0, 2.0], $c->Vec);

        $c->setError(0.3);
        self::assertSame(0.3, $c->getError());
        self::assertSame(0.3, $c->Error);

        $c->setAdjustment(0.05);
        self::assertSame(0.05, $c->getAdjustment());
        self::assertSame(0.05, $c->Adjustment);

        $c->setHeight(0.001);
        self::assertSame(0.001, $c->getHeight());
        self::assertSame(0.001, $c->Height);
    }

    public function testFluentSetters(): void
    {
        $c = new Coordinate();
        $result = $c->setVec(1.0, 2.0)->setError(0.3)->setAdjustment(0.05)->setHeight(0.001);
        self::assertSame($c, $result);
        self::assertSame([1.0, 2.0], $c->Vec);
        self::assertSame(0.3, $c->Error);
        self::assertSame(0.05, $c->Adjustment);
        self::assertSame(0.001, $c->Height);
    }

    public function testClone(): void
    {
        $c = new Coordinate(Vec: [1.0, 2.0], Error: 0.1);
        $clone = $c->Clone();
        self::assertNotSame($c, $clone);
        self::assertSame($c->Vec, $clone->Vec);
        self::assertSame($c->Error, $clone->Error);
    }

    public function testIsValid(): void
    {
        $c = new Coordinate(Vec: [1.0, 2.0], Error: 0.1, Adjustment: 0.0, Height: 0.01);
        self::assertTrue($c->IsValid());

        $invalid = new Coordinate(Vec: [NAN], Error: 0.1, Adjustment: 0.0, Height: 0.01);
        self::assertFalse($invalid->IsValid());

        $infinite = new Coordinate(Vec: [1.0], Error: INF, Adjustment: 0.0, Height: 0.01);
        self::assertFalse($infinite->IsValid());
    }

    public function testIsCompatibleWith(): void
    {
        $a = new Coordinate(Vec: [1.0, 2.0]);
        $b = new Coordinate(Vec: [3.0, 4.0]);
        self::assertTrue($a->IsCompatibleWith($b));

        $c = new Coordinate(Vec: [1.0, 2.0, 3.0]);
        self::assertFalse($a->IsCompatibleWith($c));
    }

    public function testDistanceTo(): void
    {
        $a = new Coordinate(Vec: [0.0, 0.0], Error: 0.0, Adjustment: 0.0, Height: 0.0);
        $b = new Coordinate(Vec: [3.0, 4.0], Error: 0.0, Adjustment: 0.0, Height: 0.0);
        $dist = $a->DistanceTo($b);
        self::assertGreaterThan(0, $dist->Nanoseconds());
    }

    public function testDistanceToIncompatibleThrows(): void
    {
        $a = new Coordinate(Vec: [1.0, 2.0]);
        $b = new Coordinate(Vec: [1.0, 2.0, 3.0]);
        $this->expectException(DimensionalityConflictException::class);
        $a->DistanceTo($b);
    }

    public function testApplyForceIncompatibleThrows(): void
    {
        $config = new CoordinateConfig();
        $a = new Coordinate(Vec: [1.0, 2.0]);
        $b = new Coordinate(Vec: [1.0, 2.0, 3.0]);
        $this->expectException(DimensionalityConflictException::class);
        $a->ApplyForce($config, 1.0, $b);
    }

    public function testApplyForce(): void
    {
        $config = new CoordinateConfig();
        $a = new Coordinate(Vec: [0.0, 0.0], Height: $config->HeightMin);
        $b = new Coordinate(Vec: [3.0, 4.0], Height: $config->HeightMin);
        $result = $a->ApplyForce($config, 0.5, $b);
        self::assertNotSame($a, $result);
        self::assertCount(2, $result->Vec);
    }

    public function testStaticAdd(): void
    {
        $result = Coordinate::_add([1.0, 2.0], [3.0, 4.0]);
        self::assertSame([4.0, 6.0], $result);
    }

    public function testStaticDiff(): void
    {
        $result = Coordinate::_diff([5.0, 7.0], [2.0, 3.0]);
        self::assertSame([3.0, 4.0], $result);
    }

    public function testStaticMagnitude(): void
    {
        $mag = Coordinate::_magnitude([3.0, 4.0]);
        self::assertEqualsWithDelta(5.0, $mag, 1e-10);
    }

    public function testStaticUnitVectorAt(): void
    {
        $result = Coordinate::_unitVectorAt([5.0, 0.0], [0.0, 0.0]);
        self::assertEqualsWithDelta(1.0, $result->vec[0], 1e-10);
        self::assertEqualsWithDelta(0.0, $result->vec[1], 1e-10);
        self::assertEqualsWithDelta(5.0, $result->mag, 1e-10);
    }

    public function testJsonSerialize(): void
    {
        $c = new Coordinate(Vec: [1.0, 2.0], Error: 0.5, Adjustment: 0.1, Height: 0.01);
        $out = $c->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame([1.0, 2.0], $out->Vec);
        self::assertSame(0.5, $out->Error);
        self::assertSame(0.1, $out->Adjustment);
        self::assertSame(0.01, $out->Height);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->Vec = [1.5, 2.5];
        $decoded->Error = 0.3;
        $decoded->Adjustment = 0.05;
        $decoded->Height = 0.001;
        $c = Coordinate::jsonUnserialize($decoded);
        self::assertSame([1.5, 2.5], $c->Vec);
        self::assertSame(0.3, $c->Error);
        self::assertSame(0.05, $c->Adjustment);
        self::assertSame(0.001, $c->Height);
    }

    public function testJsonRoundTrip(): void
    {
        $original = new Coordinate(Vec: [1.0, 2.0, 3.0], Error: 0.4, Adjustment: 0.02, Height: 0.003);
        $restored = Coordinate::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->Vec, $restored->Vec);
        self::assertSame($original->Error, $restored->Error);
        self::assertSame($original->Adjustment, $restored->Adjustment);
        self::assertSame($original->Height, $restored->Height);
    }
}

