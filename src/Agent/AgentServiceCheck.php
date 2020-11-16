<?php namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class AgentServiceCheck
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentServiceCheck extends AbstractModel
{
    /** @var string */
    public $CheckID = '';
    /** @var string */
    public $Name = '';
    /** @var string[] */
    public $Args = [];
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
    public function getCheckID(): string
    {
        return $this->CheckID;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $name
     * @return AgentServiceCheck
     */
    public function setName(string $name): AgentServiceCheck
    {
        $this->Name = $name;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getArgs(): array
    {
        return $this->Args;
    }

    /**
     * @param string[] $args
     * @return AgentServiceCheck
     */
    public function setArgs(array $args): AgentServiceCheck
    {
        $this->Args = $args;
        return $this;
    }

    /**
     * @param string $checkID
     * @return AgentServiceCheck
     */
    public function setCheckID(string $checkID): AgentServiceCheck
    {
        $this->CheckID = $checkID;
        return $this;
    }

    /**
     * @return string
     */
    public function getDockerContainerID(): string
    {
        return $this->DockerContainerID;
    }

    /**
     * @param string $dockerContainerID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setDockerContainerID(string $dockerContainerID): AgentServiceCheck
    {
        $this->DockerContainerID = $dockerContainerID;
        return $this;
    }

    /**
     * @return string
     */
    public function getShell(): string
    {
        return $this->Shell;
    }

    /**
     * @param string $shell
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setShell(string $shell): AgentServiceCheck
    {
        $this->Shell = $shell;
        return $this;
    }

    /**
     * @return string
     */
    public function getInterval(): string
    {
        return $this->Interval;
    }

    /**
     * @param string $interval
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setInterval(string $interval): AgentServiceCheck
    {
        $this->Interval = $interval;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeout(): string
    {
        return $this->Timeout;
    }

    /**
     * @param string $timeout
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTimeout(string $timeout): AgentServiceCheck
    {
        $this->Timeout = $timeout;
        return $this;
    }

    /**
     * @return string
     */
    public function getTTL(): string
    {
        return $this->TTL;
    }

    /**
     * @param string $ttl
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTTL(string $ttl): AgentServiceCheck
    {
        $this->TTL = $ttl;
        return $this;
    }

    /**
     * @return string
     */
    public function getHTTP(): string
    {
        return $this->HTTP;
    }

    /**
     * @param string $http
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setHTTP(string $http): AgentServiceCheck
    {
        $this->HTTP = $http;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->Header;
    }

    /**
     * @param array $header
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setHeader(array $header): AgentServiceCheck
    {
        $this->Header = $header;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->Method;
    }

    /**
     * @param string $method
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setMethod(string $method): AgentServiceCheck
    {
        $this->Method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getTCP(): string
    {
        return $this->TCP;
    }

    /**
     * @param string $tcp
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTCP(string $tcp): AgentServiceCheck
    {
        $this->TCP = $tcp;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $status
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setStatus(string $status): AgentServiceCheck
    {
        $this->Status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->Notes;
    }

    /**
     * @param string $notes
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setNotes(string $notes): AgentServiceCheck
    {
        $this->Notes = $notes;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTLSSkipVerify(): bool
    {
        return $this->TLSSkipVerify;
    }

    /**
     * @param bool $tlsSkipVerify
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setTLSSkipVerify(bool $tlsSkipVerify): AgentServiceCheck
    {
        $this->TLSSkipVerify = $tlsSkipVerify;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeregisterCriticalServiceAfter(): string
    {
        return $this->DeregisterCriticalServiceAfter;
    }

    /**
     * @param string $deregisterCriticalServiceAfter
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setDeregisterCriticalServiceAfter(string $deregisterCriticalServiceAfter): AgentServiceCheck
    {
        $this->DeregisterCriticalServiceAfter = $deregisterCriticalServiceAfter;
        return $this;
    }
}