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

class GaugeValue extends AbstractModel
{
    public string $Name;
    public float $Value;
    /** @var array<string,string> */
    public array $Labels;

    /**
     * @param array<string,string> $Labels
     */
    public function __construct(
        string $Name = '',
        float $Value = 0.0,
        array $Labels = [],
    ) {
        $this->Name = $Name;
        $this->Value = $Value;
        $this->setLabels($Labels);
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

    public function getValue(): float
    {
        return $this->Value;
    }

    public function setValue(float $value): self
    {
        $this->Value = $value;
        return $this;
    }

    /**
     * @return null|array<string,string>
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
            unset($this->Labels);
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
        foreach ($decoded as $k => $v) {
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
        $out->Value = $this->Value;
        $out->Labels = $this->getLabels();
        return $out;
    }
}
