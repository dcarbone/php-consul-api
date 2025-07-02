<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;

class PassiveHealthCheck extends AbstractModel
{
    public Time\Duration $Interval;
    public int $MaxFailures;

    public null|int $EnforcingConsecutive5xx;
    public null|int $MaxEjectionPercent;

    public null|Time\Duration $BaseEjectionTime;

    /**
     * @param array<string,mixed>|null $data
     */
    public function __construct(
        null|array $data = [],  // Deprecated, will be removed.
        null|string|int|float|\DateInterval|Time\Duration $Interval = null,
        int $MaxFailures = 0,
        null|int $EnforcingConsecutive5xx = null,
        null|int $MaxEjectionPercent = null,
        null|string|int|float|\DateInterval|Time\Duration $BaseEjectionTime = null,
    ) {
        $this->Interval = Time::Duration($Interval);
        $this->MaxFailures = $MaxFailures;
        $this->EnforcingConsecutive5xx = $EnforcingConsecutive5xx;
        $this->MaxEjectionPercent = $MaxEjectionPercent;
        $this->BaseEjectionTime = Time::Duration($BaseEjectionTime);
}

    public function getInterval(): Time\Duration
    {
        return $this->Interval;
    }

    public function setInterval(null|string|int|float|\DateInterval|Time\Duration $Interval): self
    {
        $this->Interval = Time::Duration($Interval);
        return $this;
    }

    public function getMaxFailures(): int
    {
        return $this->MaxFailures;
    }

    public function setMaxFailures(int $MaxFailures): self
    {
        $this->MaxFailures = $MaxFailures;
        return $this;
    }

    public function getEnforcingConsecutive5xx(): null|int
    {
        return $this->EnforcingConsecutive5xx;
    }

    public function setEnforcingConsecutive5xx(null|int $EnforcingConsecutive5xx): self
    {
        $this->EnforcingConsecutive5xx = $EnforcingConsecutive5xx;
        return $this;
    }

    public function getMaxEjectionPercent(): null|int
    {
        return $this->MaxEjectionPercent;
    }

    public function setMaxEjectionPercent(null|int $MaxEjectionPercent): self
    {
        $this->MaxEjectionPercent = $MaxEjectionPercent;
        return $this;
    }

    public function getBaseEjectionTime(): null|Time\Duration
    {
        return $this->BaseEjectionTime;
    }

    public function setBaseEjectionTime(null|Time\Duration $BaseEjectionTime): self
    {
        $this->BaseEjectionTime = $BaseEjectionTime;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Interval' === $k) {
                $n->Interval = Time::ParseDuration($v);
            } elseif ('max_failures' === $k) {
                $n->MaxFailures = $v;
            } elseif ('enforcing_consecutive_5xx' === $k) {
                $n->EnforcingConsecutive5xx = $v;
            } elseif ('max_ejection_percent' === $k) {
                $n->MaxEjectionPercent = $v;
            } elseif ('base_ejection_time' === $k) {
                $n->BaseEjectionTime = Time::ParseDuration($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ($this->Interval->Nanoseconds() !== 0) {
            $out->Interval = $this->Interval;
        }
        $out->MaxFailures = $this->MaxFailures;
        if (null !== $this->EnforcingConsecutive5xx) {
            $out->EnforcingConsecutive5xx = $this->EnforcingConsecutive5xx;
        }
        if (null !== $this->MaxEjectionPercent) {
            $out->MaxEjectionPercent = $this->MaxEjectionPercent;
        }
        if (null !== $this->BaseEjectionTime) {
            $out->BaseEjectionTime = $this->BaseEjectionTime;
        }
        return $out;
    }
}
