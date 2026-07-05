<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class SampledValue extends AbstractType
{
    public string $Name;
    public int $Count;
    public float $Sum;
    public float $Min;
    public float $Max;
    public float $Mean;
    public float $Stddev;
    /** @var null|array<string,string> */
    public null|array $Labels = null;

    /**
     * @param array<string,string> $Labels
     */
    public function __construct(
        string $Name = '',
        int $Count = 0,
        float $Sum = 0.0,
        float $Min = 0.0,
        float $Max = 0.0,
        float $Mean = 0.0,
        float $Stddev = 0.0,
        array $Labels = [],
    ) {
        $this->Name = $Name;
        $this->Count = $Count;
        $this->Sum = $Sum;
        $this->Min = $Min;
        $this->Max = $Max;
        $this->Mean = $Mean;
        $this->Stddev = $Stddev;
        $this->setLabels($Labels);
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getCount(): int
    {
        return $this->Count;
    }

    public function setCount(int $Count): self
    {
        $this->Count = $Count;
        return $this;
    }

    public function getSum(): float
    {
        return $this->Sum;
    }

    public function setSum(float $Sum): self
    {
        $this->Sum = $Sum;
        return $this;
    }

    public function getMin(): float
    {
        return $this->Min;
    }

    public function setMin(float $Min): self
    {
        $this->Min = $Min;
        return $this;
    }

    public function getMax(): float
    {
        return $this->Max;
    }

    public function setMax(float $Max): self
    {
        $this->Max = $Max;
        return $this;
    }

    public function getMean(): float
    {
        return $this->Mean;
    }

    public function setMean(float $Mean): self
    {
        $this->Mean = $Mean;
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

    /**
     * @return array<string,string>|null
     */
    public function getLabels(): null|array
    {
        return $this->Labels;
    }

    /**
     * @param \stdClass|array<string,string>|null $Labels
     * @return $this
     */
    public function setLabels(null|\stdClass|array $Labels): self
    {
        if (null === $Labels) {
            $this->Labels = null;
            return $this;
        }
        $this->Labels = [];
        foreach ($Labels as $k => $v) {
            $this->Labels[$k] = $v;
        }
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Labels' === $k) {
                $n->setLabels($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Name = $this->Name;
        $out->Count = $this->Count;
        $out->Sum = $this->Sum;
        $out->Min = $this->Min;
        $out->Max = $this->Max;
        $out->Mean = $this->Mean;
        $out->Stddev = $this->Stddev;
        $out->Labels = $this->getLabels();
        return $out;
    }
}
