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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

/**
 * Class Coordinate
 *
 * From github.com/hashicorp/serf/coordinate/coordinate.go
 */
class Coordinate extends AbstractType
{
    public const ZeroThreshold = 1.0e-6;
    private const secondsToNanoseconds = 1.0e9;

    /** @var array<float> */
    public array $Vec;
    public float $Error;
    public float $Adjustment;
    public float $Height;

    /**
     * @param array<float> $Vec
     */
    public function __construct(
        null|CoordinateConfig $config = null,
        array $Vec = [],
        float $Error = 0.0,
        float $Adjustment = 0.0,
        float $Height = 0.0,
    ) {
        if (null !== $config) {
            $this->Vec = array_fill(0, $config->Dimensionality, 0.0);
            $this->Error = $config->VivaldiErrorMax;
            $this->Adjustment = 0.0;
            $this->Height = $config->HeightMin;
        } else {
            $this->setVec(...$Vec);
            $this->Error = $Error;
            $this->Adjustment = $Adjustment;
            $this->Height = $Height;
        }
    }

    /**
     * @return float[]
     */
    public function getVec(): array
    {
        return $this->Vec;
    }

    public function setVec(float ...$Vec): self
    {
        $this->Vec = $Vec;
        return $this;
    }

    public function getError(): float
    {
        return $this->Error;
    }

    public function getAdjustment(): float
    {
        return $this->Adjustment;
    }

    public function getHeight(): float
    {
        return $this->Height;
    }

    public function Clone(): self
    {
        return clone $this;
    }

    public function IsValid(): bool
    {
        foreach ($this->Vec as $vec) {
            if (!self::_componentIsValid($vec)) {
                return false;
            }
        }
        return self::_componentIsValid($this->Error) &&
            self::_componentIsValid($this->Adjustment) &&
            self::_componentIsValid($this->Height);
    }

    public function IsCompatibleWith(self $other): bool
    {
        return count($this->Vec) === count($other->Vec);
    }

    public function ApplyForce(CoordinateConfig $config, float $force, self $other): self
    {
        if (!$this->IsCompatibleWith($other)) {
            throw new DimensionalityConflictException();
        }

        $ret = clone $this;
        $va = self::_unitVectorAt($this->Vec, $other->Vec);
        $ret->Vec = self::_add($ret->Vec, self::_mul($va->vec, $force));
        if ($va->mag > self::ZeroThreshold) {
            $ret->Height = max(($ret->Height + $other->Height) * $force / $va->mag + $ret->Height, $config->HeightMin);
        }

        return $ret;
    }

    public function DistanceTo(self $other): Time\Duration
    {
        if (!$this->IsCompatibleWith($other)) {
            throw new DimensionalityConflictException();
        }

        $dist = $this->rawDistanceTo($other);
        $adjustedDist = $dist + $this->Adjustment + $other->Adjustment;
        if ($adjustedDist > 0.0) {
            $dist = $adjustedDist;
        }
        return Time::Duration($dist * self::secondsToNanoseconds);
    }

    protected function rawDistanceTo(self $other): float
    {
        return self::_magnitude(self::_diff($this->Vec, $other->Vec)) + $this->Height + $other->Height;
    }

    private static function _componentIsValid(float $f): bool
    {
        return !is_nan($f) && is_finite($f);
    }

    /**
     * @param array<float> $vec1
     * @param array<float> $vec2
     * @return array<float>
     */
    private static function _add(array $vec1, array $vec2): array
    {
        $ret = [];
        foreach ($vec1 as $k => $v) {
            $ret[$k] = $v + $vec2[$k];
        }
        return $ret;
    }

    /**
     * @param array<float> $vec1
     * @param array<float> $vec2
     * @return array<float>
     */
    private static function _diff(array $vec1, array $vec2): array
    {
        $ret = [];
        foreach ($vec1 as $k => $v) {
            $ret[$k] = $v - $vec2[$k];
        }
        return $ret;
    }

    /**
     * @param array<float> $vec
     * @return array<float>
     */
    private static function _mul(array $vec, float $factor): array
    {
        $ret = [];
        foreach ($vec as $k => $v) {
            $ret[$k] = $v * $factor;
        }
        return $ret;
    }

    /**
     * @param array<float> $vec
     * @return float
     */
    private static function _magnitude(array $vec): float
    {
        $sum = 0.0;
        foreach ($vec as $k => $v) {
            $sum += ($v * $v);
        }
        return sqrt($sum);
    }

    /**
     * @param array<float> $vec1
     * @param array<float> $vec2
     */
    private static function _unitVectorAt(array $vec1, array $vec2): CoordinateUnitVectorAt
    {
        $ret = self::_diff($vec1, $vec2);

        if (($mag = self::_magnitude($ret)) && $mag > self::ZeroThreshold) {
            return new CoordinateUnitVectorAt(vec: self::_mul($ret, 1.0 / $mag), mag: $mag);
        }

        foreach ($ret as $k => &$v) {
            $v = lcg_value() - 0.5;
        }

        if (($mag = self::_magnitude($ret)) && $mag > self::ZeroThreshold) {
            return new CoordinateUnitVectorAt(vec: self::_mul($ret, 1.0 / $mag), mag: 0.0);
        }

        $ret    = array_fill(0, count($ret), 0.0);
        $ret[0] = 1.0;
        return new CoordinateUnitVectorAt(vec: $ret, mag: 0.0);
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Vec' === $k) {
                $n->Vec = (array)$v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Vec = $this->Vec;
        $out->Error = $this->Error;
        $out->Adjustment = $this->Adjustment;
        $out->Height = $this->Height;
        return $out;
    }
}
