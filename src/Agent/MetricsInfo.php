<?php namespace DCarbone\PHPConsulAPI\Agent;

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
 * Class MetricsInfo
 * @package DCarbone\PHPConsulAPI\Agent
 */
class MetricsInfo extends AbstractModel {
    /** @var string */
    public $Timestamp = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\GaugeValue[] */
    public $Gauges = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\PointValue[] */
    public $Points = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\SampledValue[] */
    public $Counters = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\SampledValue[] */
    public $Samples = [];

    /**
     * MetricsInfo constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);

        if (0 < count($this->Gauges)) {
            $this->Gauges = array_filter($this->Gauges);
            foreach ($this->Gauges as &$v) {
                if (!($v instanceof GaugeValue)) {
                    $v = new GaugeValue($v);
                }
            }
        }
        if (0 < count($this->Points)) {
            $this->Points = array_filter($this->Points);
            foreach ($this->Points as &$v) {
                if (!($v instanceof PointValue)) {
                    $v = new PointValue($v);
                }
            }
        }
        if (0 < count($this->Counters)) {
            $this->Counters = array_filter($this->Counters);
            foreach ($this->Counters as &$v) {
                if (!($v instanceof SampledValue)) {
                    $v = new SampledValue($v);
                }
            }
        }
        if (0 < count($this->Samples)) {
            $this->Samples = array_filter($this->Samples);
            foreach ($this->Samples as &$v) {
                if (!($v instanceof SampledValue)) {
                    $v = new SampledValue($v);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getTimestamp(): string {
        return $this->Timestamp;
    }

    /**
     * @param string $timestamp
     * @return MetricsInfo
     */
    public function setTimestamp(string $timestamp): MetricsInfo {
        $this->Timestamp = $timestamp;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\GaugeValue[]
     */
    public function getGauges(): array {
        return $this->Gauges;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\GaugeValue[] $gauges
     * @return MetricsInfo
     */
    public function setGauges(array $gauges): MetricsInfo {
        $this->Gauges = $gauges;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\PointValue[]
     */
    public function getPoints(): array {
        return $this->Points;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\PointValue[] $points
     * @return MetricsInfo
     */
    public function setPoints(array $points): MetricsInfo {
        $this->Points = $points;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue[]
     */
    public function getCounters(): array {
        return $this->Counters;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\SampledValue[] $counters
     * @return MetricsInfo
     */
    public function setCounters(array $counters): MetricsInfo {
        $this->Counters = $counters;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue[]
     */
    public function getSamples(): array {
        return $this->Samples;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\SampledValue[] $samples
     * @return MetricsInfo
     */
    public function setSamples(array $samples): MetricsInfo {
        $this->Samples = $samples;
        return $this;
    }
}