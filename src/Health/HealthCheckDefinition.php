<?php namespace DCarbone\PHPConsulAPI\Health;

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
use DCarbone\PHPConsulAPI\Operator\ReadableDuration;

/**
 * Class HealthCheckDefinition
 * @package DCarbone\PHPConsulAPI\Health
 */
class HealthCheckDefinition extends AbstractModel {
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
    /** @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration */
    public $Interval = null;
    /** @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration */
    public $Timeout = null;
    /** @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration */
    public $DeregisterCriticalServiceAfter = null;

    /**
     * HealthCheckDefinition constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);
        if (null === $this->Interval) {
            $this->Interval = new ReadableDuration();
        } else if (is_string($this->Interval)) {
            $this->Interval = ReadableDuration::fromDuration($this->Interval);
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Expected null or go time format string for Interval, saw %s',
                gettype($this->Interval)
            ));
        }
        if (null === $this->Timeout) {
            $this->Timeout = new ReadableDuration();
        } else if (is_string($this->Timeout)) {
            $this->Timeout = ReadableDuration::fromDuration($this->Timeout);
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Expected null or go time format string for Timeout, saw %s',
                gettype($this->Timeout)
            ));
        }
        if (null === $this->DeregisterCriticalServiceAfter) {
            $this->DeregisterCriticalServiceAfter = new ReadableDuration();
        } else if (is_string($this->DeregisterCriticalServiceAfter)) {
            $this->DeregisterCriticalServiceAfter = ReadableDuration::fromDuration($this->DeregisterCriticalServiceAfter);
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Expected null or go time format string for DeregisterCriticalServiceAfter, saw %s',
                gettype($this->DeregisterCriticalServiceAfter)
            ));
        }
    }

    /**
     * @return string
     */
    public function getHTTP(): string {
        return $this->HTTP;
    }

    /**
     * @return array
     */
    public function getHeader(): array {
        return $this->Header;
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->Method;
    }

    /**
     * @return bool
     */
    public function isTLSSkipVerify(): bool {
        return $this->TLSSkipVerify;
    }

    /**
     * @return string
     */
    public function getTCP(): string {
        return $this->TCP;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration
     */
    public function getInterval(): ReadableDuration {
        return $this->Interval;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration
     */
    public function getTimeout(): ReadableDuration {
        return $this->Timeout;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration
     */
    public function getDeregisterCriticalServiceAfter(): ReadableDuration {
        return $this->DeregisterCriticalServiceAfter;
    }
}