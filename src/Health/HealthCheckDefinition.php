<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Health;

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

use DCarbone\Go\Time;
use DCarbone\Go\Time\Duration;
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Operator\ReadableDuration;

/**
 * Class HealthCheckDefinition
 * @package DCarbone\PHPConsulAPI\Health
 */
class HealthCheckDefinition extends AbstractModel
{
    /** @var string */
    public $HTTP = '';
    /** @var array */
    public $Header = [];
    /** @var string */
    public $Method = '';
    /** @var bool */
    public $TLSSkipVerify = false;
    /** @var string */
    public $TCP = '';
    /** @var \DCarbone\Go\Time\Duration */
    public $IntervalDuration = null;
    /** @var \DCarbone\Go\Time\Duration */
    public $TimeoutDuration = null;
    /** @var \DCarbone\Go\Time\Duration */
    public $DeregisterCriticalServiceAfterDuration = null;

    /**
     * @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration
     * @deprecated use $IntervalDuration
     */
    public $Interval = null;
    /**
     * @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration
     * @deprecated use $TimeoutDuration
     */
    public $Timeout = null;
    /**
     * @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration
     * @deprecated use $DeregisterCriticalServiceAfterDuration
     */
    public $DeregisterCriticalServiceAfter = null;

    /**
     * HealthCheckDefinition constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        if (null === $this->Interval) {
            $this->Interval = new ReadableDuration();
        } elseif (is_string($this->Interval)) {
            $this->Interval = ReadableDuration::fromDuration($this->Interval);
        }
        if (null === $this->Timeout) {
            $this->Timeout = new ReadableDuration();
        } elseif (is_string($this->Timeout)) {
            $this->Timeout = ReadableDuration::fromDuration($this->Timeout);
        }
        if (null === $this->DeregisterCriticalServiceAfter) {
            $this->DeregisterCriticalServiceAfter = new ReadableDuration();
        } elseif (is_string($this->DeregisterCriticalServiceAfter)) {
            $this->DeregisterCriticalServiceAfter = ReadableDuration::fromDuration(
                $this->DeregisterCriticalServiceAfter
            );
        }

        if (null === $this->IntervalDuration) {
            $this->IntervalDuration = Time::ParseDuration((string)$this->Interval);
        } else {
            $this->Interval = ReadableDuration::fromDuration((string)$this->IntervalDuration);
        }
        if (null === $this->TimeoutDuration) {
            $this->TimeoutDuration = Time::ParseDuration((string)$this->Timeout);
        } else {
            $this->Timeout = ReadableDuration::fromDuration((string)$this->TimeoutDuration);
        }
        if (null === $this->DeregisterCriticalServiceAfterDuration) {
            $this->DeregisterCriticalServiceAfterDuration = Time::ParseDuration(
                (string)$this->DeregisterCriticalServiceAfter
            );
        } else {
            $this->DeregisterCriticalServiceAfter = ReadableDuration::fromDuration(
                (string)$this->DeregisterCriticalServiceAfterDuration
            );
        }
    }

    /**
     * @return string
     */
    public function getHTTP(): string
    {
        return $this->HTTP;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->Header;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->Method;
    }

    /**
     * @return bool
     */
    public function isTLSSkipVerify(): bool
    {
        return $this->TLSSkipVerify;
    }

    /**
     * @return string
     */
    public function getTCP(): string
    {
        return $this->TCP;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getIntervalDuration(): Duration
    {
        return $this->IntervalDuration;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getTimeoutDuration(): Duration
    {
        return $this->TimeoutDuration;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getDeregisterCriticalServiceAfterDuration(): Duration
    {
        return $this->DeregisterCriticalServiceAfterDuration;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration
     * @deprecated
     */
    public function getInterval(): ReadableDuration
    {
        return $this->Interval;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration
     * @deprecated
     */
    public function getTimeout(): ReadableDuration
    {
        return $this->Timeout;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration
     * @deprecated
     */
    public function getDeregisterCriticalServiceAfter(): ReadableDuration
    {
        return $this->DeregisterCriticalServiceAfter;
    }

    public function jsonSerialize()
    {
        $out = [
            'HTTP'          => $this->HTTP,
            'Header'        => $this->Header,
            'Method'        => $this->Method,
            'TLSSkipVerify' => $this->TLSSkipVerify,
            'TCP'           => $this->TCP,

        ];
        if (0 !== $this->IntervalDuration->Nanoseconds()) {
            $out['Interval'] = (string)$this->IntervalDuration;
        } elseif (0 !== $this->Interval->Nanoseconds()) {
            $out['Interval'] = (string)$this->Interval;
        }
        if (0 !== $this->TimeoutDuration->Nanoseconds()) {
            $out['Timeout'] = (string)$this->TimeoutDuration;
        } elseif (0 !== $this->Timeout->Nanoseconds()) {
            $out['Timeout'] = (string)$this->Timeout;
        }
        if (0 !== $this->DeregisterCriticalServiceAfterDuration->Nanoseconds()) {
            $out['DeregisterCriticalServiceAfter'] = (string)$this->DeregisterCriticalServiceAfterDuration;
        } elseif (0 !== $this->DeregisterCriticalServiceAfter->Nanoseconds()) {
            $out['DeregisterCriticalServiceAfter'] = (string)$this->DeregisterCriticalServiceAfter;
        }
        return $out;
    }
}