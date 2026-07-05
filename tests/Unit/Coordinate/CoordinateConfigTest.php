<?php

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
        self::assertSame(CoordinateConfig::DefaultDimensionality, $c->Dimensionality);
        self::assertSame(CoordinateConfig::DefaultVivaldiErrorMax, $c->getVivaldiErrorMax());
        self::assertSame(CoordinateConfig::DefaultVivaldiErrorMax, $c->VivaldiErrorMax);
        self::assertSame(CoordinateConfig::DefaultVivaldiCE, $c->getVivaldiCE());
        self::assertSame(CoordinateConfig::DefaultVivaldiCE, $c->VivaldiCE);
        self::assertSame(CoordinateConfig::DefaultVivaldiCC, $c->getVivaldiCC());
        self::assertSame(CoordinateConfig::DefaultVivaldiCC, $c->VivaldiCC);
        self::assertSame(CoordinateConfig::DefaultAdjustmentWindowSize, $c->getAdjustmentWindowSize());
        self::assertSame(CoordinateConfig::DefaultAdjustmentWindowSize, $c->AdjustmentWindowSize);
        self::assertSame(CoordinateConfig::DefaultHeightMin, $c->getHeightMin());
        self::assertSame(CoordinateConfig::DefaultHeightMin, $c->HeightMin);
        self::assertSame(CoordinateConfig::DefaultLatencyFilterSize, $c->getLatencyFilterSize());
        self::assertSame(CoordinateConfig::DefaultLatencyFilterSize, $c->LatencyFilterSize);
        self::assertSame(CoordinateConfig::DefaultGravityRho, $c->getGravityRho());
        self::assertSame(CoordinateConfig::DefaultGravityRho, $c->GravityRho);
        self::assertSame([], $c->getMetricsLabels());
        self::assertSame([], $c->MetricsLabels);
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
        self::assertSame(4, $c->Dimensionality);
        self::assertSame(2.0, $c->getVivaldiErrorMax());
        self::assertSame(2.0, $c->VivaldiErrorMax);
        self::assertSame(0.5, $c->getVivaldiCE());
        self::assertSame(0.5, $c->VivaldiCE);
        self::assertSame(0.5, $c->getVivaldiCC());
        self::assertSame(0.5, $c->VivaldiCC);
        self::assertSame(10, $c->getAdjustmentWindowSize());
        self::assertSame(10, $c->AdjustmentWindowSize);
        self::assertSame(1.0e-5, $c->getHeightMin());
        self::assertSame(1.0e-5, $c->HeightMin);
        self::assertSame(5, $c->getLatencyFilterSize());
        self::assertSame(5, $c->LatencyFilterSize);
        self::assertSame(100.0, $c->getGravityRho());
        self::assertSame(100.0, $c->GravityRho);
        self::assertCount(1, $c->getMetricsLabels());
        self::assertCount(1, $c->MetricsLabels);
        self::assertSame('env', $c->MetricsLabels[0]->getName());
    }

    public function testSettersWithDirectFieldAccess(): void
    {
        $c = new CoordinateConfig();
        $c->setDimensionality(3);
        self::assertSame(3, $c->getDimensionality());
        self::assertSame(3, $c->Dimensionality);

        $c->setVivaldiErrorMax(1.0);
        self::assertSame(1.0, $c->getVivaldiErrorMax());
        self::assertSame(1.0, $c->VivaldiErrorMax);

        $c->setVivaldiCE(0.1);
        self::assertSame(0.1, $c->getVivaldiCE());
        self::assertSame(0.1, $c->VivaldiCE);

        $c->setVivaldiCC(0.2);
        self::assertSame(0.2, $c->getVivaldiCC());
        self::assertSame(0.2, $c->VivaldiCC);

        $c->setAdjustmentWindowSize(15);
        self::assertSame(15, $c->getAdjustmentWindowSize());
        self::assertSame(15, $c->AdjustmentWindowSize);

        $c->setHeightMin(5.0e-6);
        self::assertSame(5.0e-6, $c->getHeightMin());
        self::assertSame(5.0e-6, $c->HeightMin);

        $c->setLatencyFilterSize(2);
        self::assertSame(2, $c->getLatencyFilterSize());
        self::assertSame(2, $c->LatencyFilterSize);

        $c->setGravityRho(50.0);
        self::assertSame(50.0, $c->getGravityRho());
        self::assertSame(50.0, $c->GravityRho);

        $label = new Label(Name: 'k', Value: 'v');
        $c->setMetricsLabels($label);
        self::assertCount(1, $c->getMetricsLabels());
        self::assertCount(1, $c->MetricsLabels);
        self::assertSame('k', $c->MetricsLabels[0]->getName());
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
        self::assertSame(3, $c->Dimensionality);
        self::assertCount(1, $c->MetricsLabels);
    }

    public function testDeprecatedDefault(): void
    {
        $c = CoordinateConfig::Default();
        self::assertSame(CoordinateConfig::DefaultDimensionality, $c->Dimensionality);
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
        self::assertSame(6, $c->Dimensionality);
        self::assertSame(1.0, $c->VivaldiErrorMax);
        self::assertCount(1, $c->MetricsLabels);
        self::assertInstanceOf(Label::class, $c->MetricsLabels[0]);
        self::assertSame('test', $c->MetricsLabels[0]->getName());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new CoordinateConfig(Dimensionality: 5, VivaldiErrorMax: 2.5);
        $restored = CoordinateConfig::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->Dimensionality, $restored->Dimensionality);
        self::assertSame($original->VivaldiErrorMax, $restored->VivaldiErrorMax);
    }
}

