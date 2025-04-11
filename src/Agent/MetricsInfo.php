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
use DCarbone\PHPConsulAPI\Transcoding;

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

    public string $Timestamp = '';
    public array $Gauges = [];
    public array $Points = [];
    public array $Counters = [];
    public array $Samples = [];

    public function getTimestamp(): string
    {
        return $this->Timestamp;
    }

    public function setTimestamp(string $timestamp): self
    {
        $this->Timestamp = $timestamp;
        return $this;
    }

    public function getGauges(): array
    {
        return $this->Gauges;
    }

    public function setGauges(array $gauges): self
    {
        $this->Gauges = $gauges;
        return $this;
    }

    public function getPoints(): array
    {
        return $this->Points;
    }

    public function setPoints(array $points): self
    {
        $this->Points = $points;
        return $this;
    }

    public function getCounters(): array
    {
        return $this->Counters;
    }

    public function setCounters(array $counters): self
    {
        $this->Counters = $counters;
        return $this;
    }

    public function getSamples(): array
    {
        return $this->Samples;
    }

    public function setSamples(array $samples): self
    {
        $this->Samples = $samples;
        return $this;
    }
}
