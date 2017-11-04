<?php namespace DCarbone\PHPConsulAPI\Coordinate;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class CoordinateConfig
 * @package DCarbone\PHPConsulAPI\Coordinate
 */
class CoordinateConfig extends AbstractModel {
    const DefaultDimensionality = 8;
    const DefaultVivaldiErrorMax = 1.5;
    const DefaultVivaldiCE = 0.25;
    const DefaultVivaldiCC = 0.25;
    const DefaultAdjustmentWindowSize = 20;
    const DefaultHeightMin = 10.0e-6;
    const DefaultLatencyFilterSize = 3;
    const DefaultGravityRho = 150.0;

    /** @var int */
    public $Dimensionality = 0;
    /** @var float */
    public $VivaldiErrorMax = 0.0;
    /** @var float */
    public $VivaldiCE = 0.0;
    /** @var float */
    public $VivaldiCC = 0.0;
    /** @var int */
    public $AdjustmentWithZero = 0;
    /** @var float */
    public $HeightMin = 0.0;
    /** @var int */
    public $LatencyFilterSize = 0;
    /** @var float */
    public $GravityRho = 0.0;

    /**
     * @return \DCarbone\PHPConsulAPI\Coordinate\CoordinateConfig
     */
    public static function default(): CoordinateConfig {
        return new static([
            'Dimensionality'     => static::DefaultDimensionality,
            'VivaldiErrorMax'    => static::DefaultVivaldiErrorMax,
            'VivaldiCE'          => static::DefaultVivaldiCE,
            'VivaldiCC'          => static::DefaultVivaldiCC,
            'AdjustmentWithZero' => static::DefaultAdjustmentWindowSize,
            'HeightMin'          => static::DefaultHeightMin,
            'LatencyFilterSize'  => static::DefaultAdjustmentWindowSize,
            'GravityRho'         => static::DefaultGravityRho,
        ]);
    }

    /**
     * @return int
     */
    public function getDimensionality(): int {
        return $this->Dimensionality;
    }

    /**
     * @param int $Dimensionality
     * @return CoordinateConfig
     */
    public function setDimensionality(int $Dimensionality): CoordinateConfig {
        $this->Dimensionality = $Dimensionality;
        return $this;
    }

    /**
     * @return float
     */
    public function getVivaldiErrorMax(): float {
        return $this->VivaldiErrorMax;
    }

    /**
     * @param float $VivaldiErrorMax
     * @return CoordinateConfig
     */
    public function setVivaldiErrorMax(float $VivaldiErrorMax): CoordinateConfig {
        $this->VivaldiErrorMax = $VivaldiErrorMax;
        return $this;
    }

    /**
     * @return float
     */
    public function getVivaldiCE(): float {
        return $this->VivaldiCE;
    }

    /**
     * @param float $VivaldiCE
     * @return CoordinateConfig
     */
    public function setVivaldiCE(float $VivaldiCE): CoordinateConfig {
        $this->VivaldiCE = $VivaldiCE;
        return $this;
    }

    /**
     * @return float
     */
    public function getVivaldiCC(): float {
        return $this->VivaldiCC;
    }

    /**
     * @param float $VivaldiCC
     * @return CoordinateConfig
     */
    public function setVivaldiCC(float $VivaldiCC): CoordinateConfig {
        $this->VivaldiCC = $VivaldiCC;
        return $this;
    }

    /**
     * @return int
     */
    public function getAdjustmentWithZero(): int {
        return $this->AdjustmentWithZero;
    }

    /**
     * @param int $AdjustmentWithZero
     * @return CoordinateConfig
     */
    public function setAdjustmentWithZero(int $AdjustmentWithZero): CoordinateConfig {
        $this->AdjustmentWithZero = $AdjustmentWithZero;
        return $this;
    }

    /**
     * @return float
     */
    public function getHeightMin(): float {
        return $this->HeightMin;
    }

    /**
     * @param float $HeightMin
     * @return CoordinateConfig
     */
    public function setHeightMin(float $HeightMin): CoordinateConfig {
        $this->HeightMin = $HeightMin;
        return $this;
    }

    /**
     * @return int
     */
    public function getLatencyFilterSize(): int {
        return $this->LatencyFilterSize;
    }

    /**
     * @param int $LatencyFilterSize
     * @return CoordinateConfig
     */
    public function setLatencyFilterSize(int $LatencyFilterSize): CoordinateConfig {
        $this->LatencyFilterSize = $LatencyFilterSize;
        return $this;
    }

    /**
     * @return float
     */
    public function getGravityRho(): float {
        return $this->GravityRho;
    }

    /**
     * @param float $GravityRho
     * @return CoordinateConfig
     */
    public function setGravityRho(float $GravityRho): CoordinateConfig {
        $this->GravityRho = $GravityRho;
        return $this;
    }
}