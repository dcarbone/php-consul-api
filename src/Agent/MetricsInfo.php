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

class MetricsInfo extends AbstractModel
{
    public string $Timestamp;
    /** @var \DCarbone\PHPConsulAPI\Agent\GaugeValue[] */
    public array $Gauges;
    /** @var \DCarbone\PHPConsulAPI\Agent\PointValue[] */
    public array $Points;
    /** @var \DCarbone\PHPConsulAPI\Agent\SampledValue[] */
    public array $Counters;
    /** @var \DCarbone\PHPConsulAPI\Agent\SampledValue[] */
    public array $Samples;

    public function __construct(
        string $Timestamp = '',
        iterable $Gauges = [],
        iterable $Points = [],
        iterable $Counters = [],
        iterable $Samples = [],
    ) {
        $this->Timestamp = $Timestamp;
        $this->setGauges(...$Gauges);
        $this->setPoints(...$Points);
        $this->setCounters(...$Counters);
        $this->setSamples(...$Samples);
}

    public function getTimestamp(): string
    {
        return $this->Timestamp;
    }

    public function setTimestamp(string $timestamp): self
    {
        $this->Timestamp = $timestamp;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\GaugeValue[]
     */
    public function getGauges(): array
    {
        return $this->Gauges;
    }

    public function setGauges(GaugeValue ...$gauges): self
    {
        $this->Gauges = $gauges;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\PointValue[]
     */
    public function getPoints(): array
    {
        return $this->Points;
    }

    public function setPoints(PointValue ...$points): self
    {
        $this->Points = $points;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue[]
     */
    public function getCounters(): array
    {
        return $this->Counters;
    }

    public function setCounters(SampledValue ...$counters): self
    {
        $this->Counters = $counters;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\SampledValue[]
     */
    public function getSamples(): array
    {
        return $this->Samples;
    }

    public function setSamples(SampledValue ...$samples): self
    {
        $this->Samples = $samples;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Gauges' === $k) {
                foreach ($v as $vv) {
                    $n->Gauges[] = GaugeValue::jsonUnserialize($vv);
                }
            } elseif ('Points' === $k) {
                foreach ($v as $vv) {
                    $n->Points[] = PointValue::jsonUnserialize($vv);
                }
            } elseif ('Counters' === $k) {
                foreach ($v as $vv) {
                    $n->Counters[] = SampledValue::jsonUnserialize($vv);
                }
            } elseif ('Samples' === $k) {
                foreach ($v as $vv) {
                    $n->Samples[] = SampledValue::jsonUnserialize($vv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Timestamp = $this->Timestamp;
        $out->Gauges = $this->Gauges;
        $out->Points = $this->Points;
        $out->Counters = $this->Counters;
        $out->Samples = $this->Samples;
        return $out;
    }
}
