<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class AgentServiceCheck
 */
class AgentServiceCheck extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_CHECK_ID                          => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAME                              => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_SCRIPT_ARGS                       => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::STRING,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_DOCKER_CONTAINER_ID               => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_SHELL                             => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_INTERVAL                          => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_TIMEOUT                           => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_TTL                               => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_HTTP                              => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_HEADER                            => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::MIXED,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_METHOD                            => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_BODY                              => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_TCP                               => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_STATUS                            => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NOTES                             => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_TLS_SKIP_VERIFY                   => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
        self::FIELD_GRPC                              => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_GRPC_USE_TLS                      => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
        self::FIELD_ALIAS_NODE                        => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_ALIAS_SERVICE                     => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_SUCCESS_BEFORE_PASSING            => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_FAILURES_BEFORE_CRITICAL          => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_DEREGISTER_CRITICAL_SERVICE_AFTER => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
    ];

    private const FIELD_CHECK_ID                          = 'CheckID';
    private const FIELD_NAME                              = 'Name';
    private const FIELD_SCRIPT_ARGS                       = 'ScriptArgs';
    private const FIELD_DOCKER_CONTAINER_ID               = 'DockerContainerID';
    private const FIELD_SHELL                             = 'Shell';
    private const FIELD_INTERVAL                          = 'Interval';
    private const FIELD_TIMEOUT                           = 'Timeout';
    private const FIELD_TTL                               = 'TTL';
    private const FIELD_HTTP                              = 'HTTP';
    private const FIELD_HEADER                            = 'Header';
    private const FIELD_METHOD                            = 'Method';
    private const FIELD_BODY                              = 'Body';
    private const FIELD_TCP                               = 'TCP';
    private const FIELD_STATUS                            = 'Status';
    private const FIELD_NOTES                             = 'Notes';
    private const FIELD_TLS_SKIP_VERIFY                   = 'TLSSkipVerify';
    private const FIELD_GRPC                              = 'GRPC';
    private const FIELD_GRPC_USE_TLS                      = 'GRPCUseTLS';
    private const FIELD_ALIAS_NODE                        = 'AliasNode';
    private const FIELD_ALIAS_SERVICE                     = 'AliasService';
    private const FIELD_SUCCESS_BEFORE_PASSING            = 'SuccessBeforePassing';
    private const FIELD_FAILURES_BEFORE_CRITICAL          = 'FailuresBeforeCritical';
    private const FIELD_DEREGISTER_CRITICAL_SERVICE_AFTER = 'DeregisterCriticalServiceAfter';

    /** @var string */
    public string $CheckID = '';
    /** @var string */
    public string $Name = '';
    /** @var string[] */
    public array $ScriptArgs = [];
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
    public string $GRPC = '';
    /** @var bool */
    public bool $GRPCUseTLS = false;
    /** @var string */
    public string $AliasNode = '';
    /** @var string */
    public string $AliasService = '';
    /** @var int */
    public int $SuccessBeforePassing = 0;
    /** @var int */
    public int $FailuresBeforeCritical = 0;

    /** @var string */
    public string $DeregisterCriticalServiceAfter = '';

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
    public function getScriptArgs(): array
    {
        return $this->ScriptArgs;
    }

    /**
     * @param string[] $args
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setScriptArgs(array $args): self
    {
        $this->ScriptArgs = $args;
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
    public function getGRPC(): string
    {
        return $this->GRPC;
    }

    /**
     * @param string $GRPC
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setGRPC(string $GRPC): self
    {
        $this->GRPC = $GRPC;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGRPCUseTLS(): bool
    {
        return $this->GRPCUseTLS;
    }

    /**
     * @param bool $GRPCUseTLS
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setGRPCUseTLS(bool $GRPCUseTLS): self
    {
        $this->GRPCUseTLS = $GRPCUseTLS;
        return $this;
    }

    /**
     * @return string
     */
    public function getAliasNode(): string
    {
        return $this->AliasNode;
    }

    /**
     * @param string $AliasNode
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setAliasNode(string $AliasNode): self
    {
        $this->AliasNode = $AliasNode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAliasService(): string
    {
        return $this->AliasService;
    }

    /**
     * @param string $AliasService
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setAliasService(string $AliasService): self
    {
        $this->AliasService = $AliasService;
        return $this;
    }

    /**
     * @return int
     */
    public function getSuccessBeforePassing(): int
    {
        return $this->SuccessBeforePassing;
    }

    /**
     * @param int $SuccessBeforePassing
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setSuccessBeforePassing(int $SuccessBeforePassing): self
    {
        $this->SuccessBeforePassing = $SuccessBeforePassing;
        return $this;
    }

    /**
     * @return int
     */
    public function getFailuresBeforeCritical(): int
    {
        return $this->FailuresBeforeCritical;
    }

    /**
     * @param int $FailuresBeforeCritical
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public function setFailuresBeforeCritical(int $FailuresBeforeCritical): self
    {
        $this->FailuresBeforeCritical = $FailuresBeforeCritical;
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
}
