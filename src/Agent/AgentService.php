<?php namespace DCarbone\PHPConsulAPI\Agent;

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
use DCarbone\PHPConsulAPI\HasStringTags;

/**
 * Class AgentService
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentService extends AbstractModel {
    use HasStringTags;

    /** @var string */
    public $ID = '';
    /** @var string */
    public $Service = '';
    /** @var string */
    public $Address = '';
    /** @var int */
    public $Port = 0;
    /** @var bool */
    public $EnableTagOverride = false;

    /**
     * @return string
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * @return string
     */
    public function getService() {
        return $this->Service;
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->Address;
    }

    /**
     * @return int
     */
    public function getPort() {
        return $this->Port;
    }

    /**
     * @return boolean
     */
    public function isEnableTagOverride() {
        return $this->EnableTagOverride;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->ID;
    }
}