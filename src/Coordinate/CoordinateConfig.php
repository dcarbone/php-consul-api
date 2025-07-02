<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Coordinate;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
 */

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Metrics\Label;

class CoordinateConfig extends AbstractModel
{
    public const DefaultDimensionality       = 8;
    public const DefaultVivaldiErrorMax      = 1.5;
    public const DefaultVivaldiCE            = 0.25;
    public const DefaultVivaldiCC            = 0.25;
    public const DefaultAdjustmentWindowSize = 20;
    public const DefaultHeightMin            = 10.0e-6;
    public const DefaultLatencyFilterSize    = 3;
    public const DefaultGravityRho           = 150.0;

    public int $Dimensionality;
    public float $VivaldiErrorMax;
    public float $VivaldiCE;
    public float $VivaldiCC;
    public int $AdjustmentWindowSize;
    public float $HeightMin;
    public int $LatencyFilterSize;
    public float $GravityRho;
    /** @var array<\DCarbone\PHPConsulAPI\Metrics\Label> */
    public array $MetricsLabels;

    /**
     * @param array<\DCarbone\PHPConsulAPI\Metrics\Label> $MetricsLabels
     */
    public function __construct(
        int $Dimensionality = self::DefaultDimensionality,
        float $VivaldiErrorMax = self::DefaultVivaldiErrorMax,
        float $VivaldiCE = self::DefaultVivaldiCE,
        float $VivaldiCC = self::DefaultVivaldiCC,
        int $AdjustmentWindowSize = self::DefaultAdjustmentWindowSize,
        float $HeightMin = self::DefaultHeightMin,
        int $LatencyFilterSize = self::DefaultLatencyFilterSize,
        float $GravityRho = self::DefaultGravityRho,
        array $MetricsLabels = [],
    ) {
        {
            $this->Dimensionality = $Dimensionality;
            $this->VivaldiErrorMax = $VivaldiErrorMax;
            $this->VivaldiCE = $VivaldiCE;
            $this->VivaldiCC = $VivaldiCC;
            $this->AdjustmentWindowSize = $AdjustmentWindowSize;
            $this->HeightMin = $HeightMin;
            $this->LatencyFilterSize = $LatencyFilterSize;
            $this->GravityRho = $GravityRho;
            $this->setMetricsLabels(...$MetricsLabels);
        }
    }

    /**
     * Create a new CoordinateConfig with default values.
     *
     * @deprecated Just call new CoordinateConfig() instead.
     * @return self
     */
    public static function Default(): self
    {
        return new self();
    }

    public function getDimensionality(): int
    {
        return $this->Dimensionality;
    }

    public function setDimensionality(int $dimensionality): self
    {
        $this->Dimensionality = $dimensionality;
        return $this;
    }

    public function getVivaldiErrorMax(): float
    {
        return $this->VivaldiErrorMax;
    }

    public function setVivaldiErrorMax(float $vivaldiErrorMax): self
    {
        $this->VivaldiErrorMax = $vivaldiErrorMax;
        return $this;
    }

    public function getVivaldiCE(): float
    {
        return $this->VivaldiCE;
    }

    public function setVivaldiCE(float $vivaldiCE): self
    {
        $this->VivaldiCE = $vivaldiCE;
        return $this;
    }

    public function getVivaldiCC(): float
    {
        return $this->VivaldiCC;
    }

    public function setVivaldiCC(float $vivaldiCC): self
    {
        $this->VivaldiCC = $vivaldiCC;
        return $this;
    }

    public function getAdjustmentWindowSize(): int
    {
        return $this->AdjustmentWindowSize;
    }

    public function setAdjustmentWindowSize(int $adjustmentWindowSize): self
    {
        $this->AdjustmentWindowSize = $adjustmentWindowSize;
        return $this;
    }

    public function getHeightMin(): float
    {
        return $this->HeightMin;
    }

    public function setHeightMin(float $heightMin): self
    {
        $this->HeightMin = $heightMin;
        return $this;
    }

    public function getLatencyFilterSize(): int
    {
        return $this->LatencyFilterSize;
    }

    public function setLatencyFilterSize(int $latencyFilterSize): self
    {
        $this->LatencyFilterSize = $latencyFilterSize;
        return $this;
    }

    public function getGravityRho(): float
    {
        return $this->GravityRho;
    }

    public function setGravityRho(float $gravityRho): self
    {
        $this->GravityRho = $gravityRho;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\Metrics\Label>
     */
    public function getMetricsLabels(): array
    {
        return $this->MetricsLabels;
    }

    public function setMetricsLabels(Label ...$MetricsLabels): self
    {
        $this->MetricsLabels = $MetricsLabels;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('MetricsLabels' === $k) {
                $n->MetricsLabels = [];
                foreach ($v as $vv) {
                    $n->MetricsLabels[] = Label::jsonUnserialize($vv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Dimensionality = $this->Dimensionality;
        $out->VivaldiErrorMax = $this->VivaldiErrorMax;
        $out->VivaldiCE = $this->VivaldiCE;
        $out->VivaldiCC = $this->VivaldiCC;
        $out->AdjustmentWindowSize = $this->AdjustmentWindowSize;
        $out->HeightMin = $this->HeightMin;
        $out->LatencyFilterSize = $this->LatencyFilterSize;
        $out->GravityRho = $this->GravityRho;
        $out->MetricsLabels = $this->MetricsLabels;
        return $out;
    }
}
