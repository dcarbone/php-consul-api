<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 */
class SampledValue extends AbstractModel
{
    /** @var string */
    public string $Name = '';
    /** @var int */
    public int $Count = 0;
    /** @var float */
    public float $Sum = 0.0;
    /** @var float */
    public float $Min = 0.0;
    /** @var float */
    public float $Max = 0.0;
    /** @var float */
    public float $Mean = 0.0;
    /** @var float */
    public float $Stddev = 0.0;
    /** @var array */
    public array $Labels = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $name
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue
     */
    public function setName(string $name): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue
     */
    public function setCount(int $count): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue
     */
    public function setSum(float $sum): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue
     */
    public function setMin(float $min): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue
     */
    public function setMax(float $max): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue
     */
    public function setMean(float $mean): self
    {
        $this->Mean = $mean;
        return $this;
    }

    /**
     * @return float
     */
    public function getStddev(): float
    {
        return $this->Stddev;
    }

    /**
     * @param float $Stddev
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue
     */
    public function setStddev(float $Stddev): self
    {
        $this->Stddev = $Stddev;
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
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue
     */
    public function setLabels(array $labels): self
    {
        $this->Labels = $labels;
        return $this;
    }
}
