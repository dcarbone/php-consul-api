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
 * @package DCarbone\PHPConsulAPI\Agent
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
        self::FIELD_ARGS   => [
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
     * @param string $CheckID
     * @return AgentServiceCheck
     */
    public function setCheckID(string $CheckID): AgentServiceCheck
    {
        $this->CheckID = $CheckID;
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
     * @param string $Name
     * @return AgentServiceCheck
     */
    public function setName(string $Name): AgentServiceCheck
    {
        $this->Name = $Name;
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
     * @param string[] $Args
     * @return AgentServiceCheck
     */
    public function setArgs(array $Args): AgentServiceCheck
    {
        $this->Args = $Args;
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
     * @param string $DockerContainerID
     * @return AgentServiceCheck
     */
    public function setDockerContainerID(string $DockerContainerID): AgentServiceCheck
    {
        $this->DockerContainerID = $DockerContainerID;
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
     * @param string $Shell
     * @return AgentServiceCheck
     */
    public function setShell(string $Shell): AgentServiceCheck
    {
        $this->Shell = $Shell;
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
     * @param string $Interval
     * @return AgentServiceCheck
     */
    public function setInterval(string $Interval): AgentServiceCheck
    {
        $this->Interval = $Interval;
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
     * @param string $Timeout
     * @return AgentServiceCheck
     */
    public function setTimeout(string $Timeout): AgentServiceCheck
    {
        $this->Timeout = $Timeout;
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
     * @param string $TTL
     * @return AgentServiceCheck
     */
    public function setTTL(string $TTL): AgentServiceCheck
    {
        $this->TTL = $TTL;
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
     * @param string $HTTP
     * @return AgentServiceCheck
     */
    public function setHTTP(string $HTTP): AgentServiceCheck
    {
        $this->HTTP = $HTTP;
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
     * @param array $Header
     * @return AgentServiceCheck
     */
    public function setHeader(array $Header): AgentServiceCheck
    {
        $this->Header = $Header;
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
     * @param string $Method
     * @return AgentServiceCheck
     */
    public function setMethod(string $Method): AgentServiceCheck
    {
        $this->Method = $Method;
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
     * @param string $TCP
     * @return AgentServiceCheck
     */
    public function setTCP(string $TCP): AgentServiceCheck
    {
        $this->TCP = $TCP;
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
     * @param string $Status
     * @return AgentServiceCheck
     */
    public function setStatus(string $Status): AgentServiceCheck
    {
        $this->Status = $Status;
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
     * @param string $Notes
     * @return AgentServiceCheck
     */
    public function setNotes(string $Notes): AgentServiceCheck
    {
        $this->Notes = $Notes;
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
     * @param bool $TLSSkipVerify
     * @return AgentServiceCheck
     */
    public function setTLSSkipVerify(bool $TLSSkipVerify): AgentServiceCheck
    {
        $this->TLSSkipVerify = $TLSSkipVerify;
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
     * @param string $DeregisterCriticalServiceAfter
     * @return AgentServiceCheck
     */
    public function setDeregisterCriticalServiceAfter(string $DeregisterCriticalServiceAfter): AgentServiceCheck
    {
        $this->DeregisterCriticalServiceAfter = $DeregisterCriticalServiceAfter;
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