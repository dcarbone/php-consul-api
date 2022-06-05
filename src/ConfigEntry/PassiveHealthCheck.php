<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class PassiveHealthCheck
 */
class PassiveHealthCheck extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_INTERVAL => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_DURATION,
            Transcoding::FIELD_OMITEMPTY          => true,
        ],
    ];

    private const FIELD_INTERVAL = 'Interval';

    /** @var \DCarbone\Go\Time\Duration */
    public Time\Duration $Interval;
    /** @var int */
    public int $MaxFailures = 0;

    /**
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Interval)) {
            $this->Interval = new Time\Duration();
        }
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getInterval(): Time\Duration
    {
        return $this->Interval;
    }

    /**
     * @param \DCarbone\Go\Time\Duration $Interval
     * @return PassiveHealthCheck
     */
    public function setInterval(Time\Duration $Interval): self
    {
        $this->Interval = $Interval;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxFailures(): int
    {
        return $this->MaxFailures;
    }

    /**
     * @param int $MaxFailures
     * @return PassiveHealthCheck
     */
    public function setMaxFailures(int $MaxFailures): self
    {
        $this->MaxFailures = $MaxFailures;
        return $this;
    }
}
