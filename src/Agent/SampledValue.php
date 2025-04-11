<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

class SampledValue extends AbstractModel
{
    public string $Name;
    public int $Count;
    public float $Sum;
    public float $Min;
    public float $Max;
    public float $Mean;
    public float $Stddev;
    public array $Labels;

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $name): self
    {
        $this->Name = $name;
        return $this;
    }

    public function getCount(): int
    {
        return $this->Count;
    }

    public function setCount(int $count): self
    {
        $this->Count = $count;
        return $this;
    }

    public function getSum(): float
    {
        return $this->Sum;
    }

    public function setSum(float $sum): self
    {
        $this->Sum = $sum;
        return $this;
    }

    public function getMin(): float
    {
        return $this->Min;
    }

    public function setMin(float $min): self
    {
        $this->Min = $min;
        return $this;
    }

    public function getMax(): float
    {
        return $this->Max;
    }

    public function setMax(float $max): self
    {
        $this->Max = $max;
        return $this;
    }

    public function getMean(): float
    {
        return $this->Mean;
    }

    public function setMean(float $mean): self
    {
        $this->Mean = $mean;
        return $this;
    }

    public function getStddev(): float
    {
        return $this->Stddev;
    }

    public function setStddev(float $Stddev): self
    {
        $this->Stddev = $Stddev;
        return $this;
    }

    public function getLabels(): array
    {
        return $this->Labels;
    }

    public function setLabels(array $labels): self
    {
        $this->Labels = $labels;
        return $this;
    }
}
