<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Coordinate;

use DCarbone\PHPConsulAPI\Coordinate\CoordinateConfig;
use DCarbone\PHPConsulAPI\Metrics\Label;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CoordinateConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new CoordinateConfig();
        self::assertSame(CoordinateConfig::DefaultDimensionality, $c->getDimensionality());
        self::assertSame(CoordinateConfig::DefaultVivaldiErrorMax, $c->getVivaldiErrorMax());
        self::assertSame(CoordinateConfig::DefaultVivaldiCE, $c->getVivaldiCE());
        self::assertSame(CoordinateConfig::DefaultVivaldiCC, $c->getVivaldiCC());
        self::assertSame(CoordinateConfig::DefaultAdjustmentWindowSize, $c->getAdjustmentWindowSize());
        self::assertSame(CoordinateConfig::DefaultHeightMin, $c->getHeightMin());
        self::assertSame(CoordinateConfig::DefaultLatencyFilterSize, $c->getLatencyFilterSize());
        self::assertSame(CoordinateConfig::DefaultGravityRho, $c->getGravityRho());
        self::assertSame([], $c->getMetricsLabels());
    }

    public function testConstructorWithValues(): void
    {
        $label = new Label(Name: 'env', Value: 'test');
        $c = new CoordinateConfig(
            Dimensionality: 4,
            VivaldiErrorMax: 2.0,
            VivaldiCE: 0.5,
            VivaldiCC: 0.5,
            AdjustmentWindowSize: 10,
            HeightMin: 1.0e-5,
            LatencyFilterSize: 5,
            GravityRho: 100.0,
            MetricsLabels: [$label],
        );
        self::assertSame(4, $c->getDimensionality());
        self::assertSame(2.0, $c->getVivaldiErrorMax());
        self::assertSame(0.5, $c->getVivaldiCE());
        self::assertSame(0.5, $c->getVivaldiCC());
        self::assertSame(10, $c->getAdjustmentWindowSize());
        self::assertSame(1.0e-5, $c->getHeightMin());
        self::assertSame(5, $c->getLatencyFilterSize());
        self::assertSame(100.0, $c->getGravityRho());
        self::assertCount(1, $c->getMetricsLabels());
        self::assertSame('env', $c->getMetricsLabels()[0]->getName());
    }

    public function testFluentSetters(): void
    {
        $c = new CoordinateConfig();
        $result = $c
            ->setDimensionality(3)
            ->setVivaldiErrorMax(1.0)
            ->setVivaldiCE(0.1)
            ->setVivaldiCC(0.2)
            ->setAdjustmentWindowSize(15)
            ->setHeightMin(5.0e-6)
            ->setLatencyFilterSize(2)
            ->setGravityRho(50.0)
            ->setMetricsLabels(new Label(Name: 'k', Value: 'v'));
        self::assertSame($c, $result);
        self::assertSame(3, $c->getDimensionality());
        self::assertCount(1, $c->getMetricsLabels());
    }

    public function testDeprecatedDefault(): void
    {
        $c = CoordinateConfig::Default();
        self::assertSame(CoordinateConfig::DefaultDimensionality, $c->getDimensionality());
    }

    public function testJsonSerialize(): void
    {
        $c = new CoordinateConfig(Dimensionality: 4);
        $out = $c->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(4, $out->Dimensionality);
    }

    public function testJsonUnserialize(): void
    {
        $labelObj = new \stdClass();
        $labelObj->Name = 'test';
        $labelObj->Value = 'val';

        $decoded = new \stdClass();
        $decoded->Dimensionality = 6;
        $decoded->VivaldiErrorMax = 1.0;
        $decoded->VivaldiCE = 0.3;
        $decoded->VivaldiCC = 0.4;
        $decoded->AdjustmentWindowSize = 25;
        $decoded->HeightMin = 2.0e-6;
        $decoded->LatencyFilterSize = 4;
        $decoded->GravityRho = 200.0;
        $decoded->MetricsLabels = [$labelObj];

        $c = CoordinateConfig::jsonUnserialize($decoded);
        self::assertSame(6, $c->getDimensionality());
        self::assertSame(1.0, $c->getVivaldiErrorMax());
        self::assertCount(1, $c->getMetricsLabels());
        self::assertInstanceOf(Label::class, $c->getMetricsLabels()[0]);
        self::assertSame('test', $c->getMetricsLabels()[0]->getName());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new CoordinateConfig(Dimensionality: 5, VivaldiErrorMax: 2.5);
        $restored = CoordinateConfig::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->getDimensionality(), $restored->getDimensionality());
        self::assertSame($original->getVivaldiErrorMax(), $restored->getVivaldiErrorMax());
    }
}

