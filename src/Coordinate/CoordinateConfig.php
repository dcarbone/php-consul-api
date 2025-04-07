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

    public int $Dimensionality = 0;
    public float $VivaldiErrorMax = 0.0;
    public float $VivaldiCE = 0.0;
    public float $VivaldiCC = 0.0;
    public int $AdjustmentWindowSize = 0;
    public float $HeightMin = 0.0;
    public int $LatencyFilterSize = 0;
    public float $GravityRho = 0.0;

    public static function Default(): self
    {
        return new static(
            [
                'Dimensionality'       => static::DefaultDimensionality,
                'VivaldiErrorMax'      => static::DefaultVivaldiErrorMax,
                'VivaldiCE'            => static::DefaultVivaldiCE,
                'VivaldiCC'            => static::DefaultVivaldiCC,
                'AdjustmentWindowSize' => static::DefaultAdjustmentWindowSize,
                'HeightMin'            => static::DefaultHeightMin,
                'LatencyFilterSize'    => static::DefaultLatencyFilterSize,
                'GravityRho'           => static::DefaultGravityRho,
            ]
        );
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
}
