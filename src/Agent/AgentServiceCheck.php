<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AgentServiceCheck
 */
class AgentServiceCheck extends AbstractModel
{
    private const FIELD_ARGS   = 'Args';
    private const FIELD_HEADER = 'Header';

    /** @var string */
    public string $CheckID = '';
    /** @var string */
    public string $Name = '';
    /** @var string[] */
    public array $Args = [];
    /** @var string */
    public string $DockerContainerID = '';
    /** @var string */
    public string $Shell = '';
    /** @var string */
    public string $Interval = '';
    /** @var string */
    public string $Timeout = '';
    /** @var string */
    public string $TTL = '';
    /** @var string */
    public string $HTTP = '';
    /** @var array */
    public array $Header = [];
    /** @var string */
    public string $Method = '';
    /** @var string */
    public string $TCP = '';
    /** @var string */
    public string $Status = '';
    /** @var string */
    public string $Notes = '';
    /** @var bool */
    public bool $TLSSkipVerify = false;
    /** @var string */
    public string $DeregisterCriticalServiceAfter = '';

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_ARGS => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_ARRAY_TYPE => Hydration::STRING,
        ],
        self::FIELD_HEADER => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_ARRAY_TYPE => Hydration::STRING,
        ],
    ];

    /**
     * @return string
     */
    public function getCheckID(): string
    {
        return $this->CheckID;
    }

    /**
     * @param string $checkID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setCheckID(string $checkID): self
    {
        $this->CheckID = $checkID;
        return $this;
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setName(string $name): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setArgs(array $args): self
    {
        $this->Args = $args;
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
    public function setDockerContainerID(string $dockerContainerID): self
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
    public function setShell(string $shell): self
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
    public function setInterval(string $interval): self
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
    public function setTimeout(string $timeout): self
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
    public function setTTL(string $ttl): self
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
    public function setHTTP(string $http): self
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
    public function setHeader(array $header): self
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
    public function setMethod(string $method): self
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
    public function setTCP(string $tcp): self
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
    public function setStatus(string $status): self
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
    public function setNotes(string $notes): self
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
    public function setTLSSkipVerify(bool $tlsSkipVerify): self
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
    public function setDeregisterCriticalServiceAfter(string $deregisterCriticalServiceAfter): self
    {
        $this->DeregisterCriticalServiceAfter = $deregisterCriticalServiceAfter;
        return $this;
    }

    /**
     * @return array[]
     */
    public static function getFields(): array
    {
        return self::$fields;
    }

    /**
     * @param array[] $fields
     */
    public static function setFields(array $fields): void
    {
        self::$fields = $fields;
    }
}
