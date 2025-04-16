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
    public null|\stdClass $Labels;

    /**
     * @param array<string, mixed>|null $data Deprecated, will be removed.
     * @param string $Name
     * @param int $Count
     * @param float $Sum
     * @param float $Min
     * @param float $Max
     * @param float $Mean
     * @param float $Stddev
     * @param \stdClass|null $Labels
     */
    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $Name = '',
        int $Count = 0,
        float $Sum = 0.0,
        float $Min = 0.0,
        float $Max = 0.0,
        float $Mean = 0.0,
        float $Stddev = 0.0,
        null|\stdClass $Labels = null,
    ) {
        $this->Name = $Name;
        $this->Count = $Count;
        $this->Sum = $Sum;
        $this->Min = $Min;
        $this->Max = $Max;
        $this->Mean = $Mean;
        $this->Stddev = $Stddev;
        $this->Labels = $Labels ?? new \stdClass();
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
        }
    }

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

    public function setStddev(float $stddev): self
    {
        $this->Stddev = $stddev;
        return $this;
    }

    public function getLabels(): null|\stdClass
    {
        return $this->Labels;
    }

    public function setLabels(null|\stdClass $labels): self
    {
        $this->Labels = $labels ?? new \stdClass();
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->Name = $this->Name;
        $out->Count = $this->Count;
        $out->Sum = $this->Sum;
        $out->Min = $this->Min;
        $out->Max = $this->Max;
        $out->Mean = $this->Mean;
        $out->Stddev = $this->Stddev;
        $out->Labels = $this->Labels;
        return $out;
    }
}
