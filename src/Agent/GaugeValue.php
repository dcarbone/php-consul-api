<?php namespace DCarbone\PHPConsulAPI\Agent;

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
 * Class GaugeValue
 * @package DCarbone\PHPConsulAPI\Agent
 */
class GaugeValue extends AbstractModel {
    /** @var string */
    public $Name = '';
    /** @var float */
    public $Value = 0.0;
    /** @var array */
    public $Labels = [];

    /**
     * @return string
     */
    public function getName(): string {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return GaugeValue
     */
    public function setName(string $Name): GaugeValue {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): float {
        return $this->Value;
    }

    /**
     * @param float $Value
     * @return GaugeValue
     */
    public function setValue(float $Value): GaugeValue {
        $this->Value = $Value;
        return $this;
    }

    /**
     * @return array
     */
    public function getLabels(): array {
        return $this->Labels;
    }

    /**
     * @param array $Labels
     * @return GaugeValue
     */
    public function setLabels(array $Labels): GaugeValue {
        $this->Labels = $Labels;
        return $this;
    }
}