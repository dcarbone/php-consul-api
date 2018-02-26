<?php namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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
            if (0 < ($cnt = count($this->Gauges))) {
                for ($i = 0; $i < $cnt; $i++) {
                    if (!($this->Gauges[$i] instanceof GaugeValue)) {
                        $this->Gauges[$i] = new GaugeValue($this->Gauges[$i]);
                    }
                }
            }
        }
        if (0 < count($this->Points)) {
            $this->Points = array_filter($this->Points);
            if (0 < ($cnt = count($this->Points))) {
                for ($i = 0; $i < $cnt; $i++) {
                    if (!($this->Points[$i] instanceof PointValue)) {
                        $this->Points[$i] = new PointValue($this->Points[$i]);
                    }
                }
            }
        }
        if (0 < count($this->Counters)) {
            $this->Counters = array_filter($this->Counters);
            if (0 < ($cnt = count($this->Counters))) {
                for ($i = 0; $i < $cnt; $i++) {
                    if (!($this->Counters[$i] instanceof SampledValue)) {
                        $this->Counters[$i] = new SampledValue($this->Counters[$i]);
                    }
                }
            }
        }
        if (0 < count($this->Samples)) {
            $this->Samples = array_filter($this->Samples);
            if (0 < ($cnt = count($this->Samples))) {
                for ($i = 0; $i < $cnt; $i++) {
                    if (!($this->Samples[$i] instanceof SampledValue)) {
                        $this->Samples[$i] = new SampledValue($this->Samples[$i]);
                    }
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
     * @param string $Timestamp
     * @return MetricsInfo
     */
    public function setTimestamp(string $Timestamp): MetricsInfo {
        $this->Timestamp = $Timestamp;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\GaugeValue[]
     */
    public function getGauges(): array {
        return $this->Gauges;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\GaugeValue[] $Gauges
     * @return MetricsInfo
     */
    public function setGauges(array $Gauges): MetricsInfo {
        $this->Gauges = $Gauges;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\PointValue[]
     */
    public function getPoints(): array {
        return $this->Points;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\PointValue[] $Points
     * @return MetricsInfo
     */
    public function setPoints(array $Points): MetricsInfo {
        $this->Points = $Points;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue[]
     */
    public function getCounters(): array {
        return $this->Counters;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\SampledValue[] $Counters
     * @return MetricsInfo
     */
    public function setCounters(array $Counters): MetricsInfo {
        $this->Counters = $Counters;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue[]
     */
    public function getSamples(): array {
        return $this->Samples;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\SampledValue[] $Samples
     * @return MetricsInfo
     */
    public function setSamples(array $Samples): MetricsInfo {
        $this->Samples = $Samples;
        return $this;
    }
}