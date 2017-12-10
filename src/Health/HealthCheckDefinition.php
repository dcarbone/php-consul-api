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

        }
        if (null === $this->Timeout) {
            $this->Timeout = new ReadableDuration();
        }
        if (null === $this->DeregisterCriticalServiceAfter) {
            $this->DeregisterCriticalServiceAfter = new ReadableDuration();
        }
    }
}