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

/**
 * Class AgentServiceCheck
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentServiceCheck extends AbstractModel {
    /** @var string */
    public $Script = '';
    /** @var string */
    public $DockerContainerID = '';
    /** @var string */
    public $Shell = '';
    /** @var string */
    public $Interval = '';
    /** @var string */
    public $Timeout = '';
    /** @var string */
    public $TTL = '';
    /** @var string */
    public $HTTP = '';
    /** @var string */
    public $TCP = '';
    /** @var string */
    public $Status = '';

    /**
     * @return string
     */
    public function getScript() {
        return $this->Script;
    }

    /**
     * @param string $Script
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setScript($Script) {
        $this->Script = $Script;
        return $this;
    }

    /**
     * @return string
     */
    public function getDockerContainerID() {
        return $this->DockerContainerID;
    }

    /**
     * @param string $DockerContainerID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setDockerContainerID($DockerContainerID) {
        $this->DockerContainerID = $DockerContainerID;
        return $this;
    }

    /**
     * @return string
     */
    public function getShell() {
        return $this->Shell;
    }

    /**
     * @param string $Shell
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setShell($Shell) {
        $this->Shell = $Shell;
        return $this;
    }

    /**
     * @return string
     */
    public function getInterval() {
        return $this->Interval;
    }

    /**
     * @param string $Interval
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setInterval($Interval) {
        $this->Interval = $Interval;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeout() {
        return $this->Timeout;
    }

    /**
     * @param string $Timeout
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTimeout($Timeout) {
        $this->Timeout = $Timeout;
        return $this;
    }

    /**
     * @return string
     */
    public function getTTL() {
        return $this->TTL;
    }

    /**
     * @param string $TTL
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTTL($TTL) {
        $this->TTL = $TTL;
        return $this;
    }

    /**
     * @return string
     */
    public function getHTTP() {
        return $this->HTTP;
    }

    /**
     * @param string $HTTP
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setHTTP($HTTP) {
        $this->HTTP = $HTTP;
        return $this;
    }

    /**
     * @return string
     */
    public function getTCP() {
        return $this->TCP;
    }

    /**
     * @param string $TCP
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTCP($TCP) {
        $this->TCP = $TCP;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setStatus($Status) {
        $this->Status = $Status;
        return $this;
    }
}