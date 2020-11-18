<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class SampledValue
 * @package DCarbone\PHPConsulAPI\Agent
 */
class SampledValue extends AbstractModel
{
    /** @var string */
    public $Name = '';
    /** @var int */
    public $Count = 0;
    /** @var float */
    public $Sum = 0.0;
    /** @var float */
    public $Min = 0.0;
    /** @var float */
    public $Max = 0.0;
    /** @var float */
    public $Mean = 0.0;
    /** @var array */
    public $Labels = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $name
     * @return SampledValue
     */
    public function setName(string $name): SampledValue
    {
        $this->Name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->Count;
    }

    /**
     * @param int $count
     * @return SampledValue
     */
    public function setCount(int $count): SampledValue
    {
        $this->Count = $count;
        return $this;
    }

    /**
     * @return float
     */
    public function getSum(): float
    {
        return $this->Sum;
    }

    /**
     * @param float $sum
     * @return SampledValue
     */
    public function setSum(float $sum): SampledValue
    {
        $this->Sum = $sum;
        return $this;
    }

    /**
     * @return float
     */
    public function getMin(): float
    {
        return $this->Min;
    }

    /**
     * @param float $min
     * @return SampledValue
     */
    public function setMin(float $min): SampledValue
    {
        $this->Min = $min;
        return $this;
    }

    /**
     * @return float
     */
    public function getMax(): float
    {
        return $this->Max;
    }

    /**
     * @param float $max
     * @return SampledValue
     */
    public function setMax(float $max): SampledValue
    {
        $this->Max = $max;
        return $this;
    }

    /**
     * @return float
     */
    public function getMean(): float
    {
        return $this->Mean;
    }

    /**
     * @param float $mean
     * @return SampledValue
     */
    public function setMean(float $mean): SampledValue
    {
        $this->Mean = $mean;
        return $this;
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return $this->Labels;
    }

    /**
     * @param array $labels
     * @return SampledValue
     */
    public function setLabels(array $labels): SampledValue
    {
        $this->Labels = $labels;
        return $this;
    }
}