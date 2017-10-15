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
    /** @var array */
    public $Header = [];
    /** @var string */
    public $Method = '';
    /** @var string */
    public $TCP = '';
    /** @var string */
    public $Status = '';
    /** @var string */
    public $Notes = '';
    /** @var bool */
    public $TLSSkipVerify = false;
    /** @var string */
    public $DeregisterCriticalServiceAfter = '';

    /**
     * @return string
     */
    public function getScript(): string {
        return $this->Script;
    }

    /**
     * @param string $Script
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setScript(string $Script): AgentServiceCheck {
        $this->Script = $Script;
        return $this;
    }

    /**
     * @return string
     */
    public function getDockerContainerID(): string {
        return $this->DockerContainerID;
    }

    /**
     * @param string $DockerContainerID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setDockerContainerID(string $DockerContainerID): AgentServiceCheck {
        $this->DockerContainerID = $DockerContainerID;
        return $this;
    }

    /**
     * @return string
     */
    public function getShell(): string {
        return $this->Shell;
    }

    /**
     * @param string $Shell
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setShell(string $Shell): AgentServiceCheck {
        $this->Shell = $Shell;
        return $this;
    }

    /**
     * @return string
     */
    public function getInterval(): string {
        return $this->Interval;
    }

    /**
     * @param string $Interval
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setInterval(string $Interval): AgentServiceCheck {
        $this->Interval = $Interval;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeout(): string {
        return $this->Timeout;
    }

    /**
     * @param string $Timeout
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTimeout(string $Timeout): AgentServiceCheck {
        $this->Timeout = $Timeout;
        return $this;
    }

    /**
     * @return string
     */
    public function getTTL(): string {
        return $this->TTL;
    }

    /**
     * @param string $TTL
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTTL(string $TTL): AgentServiceCheck {
        $this->TTL = $TTL;
        return $this;
    }

    /**
     * @return string
     */
    public function getHTTP(): string {
        return $this->HTTP;
    }

    /**
     * @param string $HTTP
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setHTTP(string $HTTP): AgentServiceCheck {
        $this->HTTP = $HTTP;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeader(): array {
        return $this->Header;
    }

    /**
     * @param array $Header
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setHeader(array $Header): AgentServiceCheck {
        $this->Header = $Header;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->Method;
    }

    /**
     * @param string $Method
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setMethod(string $Method): AgentServiceCheck {
        $this->Method = $Method;
        return $this;
    }

    /**
     * @return string
     */
    public function getTCP(): string {
        return $this->TCP;
    }

    /**
     * @param string $TCP
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTCP(string $TCP): AgentServiceCheck {
        $this->TCP = $TCP;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setStatus(string $Status): AgentServiceCheck {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes(): string {
        return $this->Notes;
    }

    /**
     * @param string $Notes
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setNotes(string $Notes): AgentServiceCheck {
        $this->Notes = $Notes;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTLSSkipVerify(): bool {
        return $this->TLSSkipVerify;
    }

    /**
     * @param bool $TLSSkipVerify
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTLSSkipVerify(bool $TLSSkipVerify): AgentServiceCheck {
        $this->TLSSkipVerify = $TLSSkipVerify;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeregisterCriticalServiceAfter(): string {
        return $this->DeregisterCriticalServiceAfter;
    }

    /**
     * @param string $DeregisterCriticalServiceAfter
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setDeregisterCriticalServiceAfter(string $DeregisterCriticalServiceAfter): AgentServiceCheck {
        $this->DeregisterCriticalServiceAfter = $DeregisterCriticalServiceAfter;
        return $this;
    }
}