<?php

declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class MetricsInfo
 */
class MetricsInfo extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_GAUGES => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => GaugeValue::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
        self::FIELD_POINTS => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => PointValue::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
        self::FIELD_COUNTERS => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => SampledValue::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
        self::FIELD_SAMPLES => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => SampledValue::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
    ];

    private const FIELD_GAUGES   = 'Gauges';
    private const FIELD_POINTS   = 'Points';
    private const FIELD_COUNTERS = 'Counters';
    private const FIELD_SAMPLES  = 'Samples';

    /** @var string */
    public string $Timestamp = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\GaugeValue[] */
    public array $Gauges = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\PointValue[] */
    public array $Points = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\SampledValue[] */
    public array $Counters = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\SampledValue[] */
    public array $Samples = [];

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->Timestamp;
    }

    /**
     * @param string $timestamp
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfo
     */
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

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\GaugeValue[] $gauges
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfo
     */
    public function setGauges(array $gauges): self
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

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\PointValue[] $points
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfo
     */
    public function setPoints(array $points): self
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

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\SampledValue[] $counters
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfo
     */
    public function setCounters(array $counters): self
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

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\SampledValue[] $samples
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfo
     */
    public function setSamples(array $samples): self
    {
        $this->Samples = $samples;
        return $this;
    }
}
