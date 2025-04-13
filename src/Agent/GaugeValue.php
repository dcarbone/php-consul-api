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
    public array $Labels;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $Name = '',
        float $Value = 0.0,
        array|\stdClass $Labels = [],
    ) {
        $this->Name = $Name;
        $this->Value = $Value;
        $this->setLabels($Labels);
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

    public function getValue(): float
    {
        return $this->Value;
    }

    public function setValue(float $value): self
    {
        $this->Value = $value;
        return $this;
    }

    public function getLabels(): array
    {
        return $this->Labels;
    }

    public function setLabels(array|\stdClass $labels): self
    {
        $this->Labels = (array)$labels;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new static();
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
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->Name = $this->Name;
        $out->Value = $this->Value;
        $out->Labels = $this->Labels;
        return $out;
    }
}
