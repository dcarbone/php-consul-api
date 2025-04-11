<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

    public string $CheckID;
    public string $Name;
    public array $ScriptArgs;
    public string $DockerContainerID;
    public string $Shell;
    public string $Interval;
    public string $Timeout;
    public string $TTL = '';
    public string $HTTP = '';
    public array $Header = [];
    public string $Method = '';
    public string $TCP = '';
    public string $Status = '';
    public string $Notes = '';
    public bool $TLSSkipVerify = false;
    public string $GRPC = '';
    public bool $GRPCUseTLS = false;
    public string $AliasNode = '';
    public string $AliasService = '';
    public int $SuccessBeforePassing = 0;
    public int $FailuresBeforeCritical = 0;

    public string $DeregisterCriticalServiceAfter = '';

    public function getCheckID(): string
    {
        return $this->CheckID;
    }

    public function setCheckID(string $checkID): self
    {
        $this->CheckID = $checkID;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $name): self
    {
        $this->Name = $name;
        return $this;
    }

    public function getScriptArgs(): array
    {
        return $this->ScriptArgs;
    }

    public function setScriptArgs(array $args): self
    {
        $this->ScriptArgs = $args;
        return $this;
    }

    public function getDockerContainerID(): string
    {
        return $this->DockerContainerID;
    }

    public function setDockerContainerID(string $dockerContainerID): self
    {
        $this->DockerContainerID = $dockerContainerID;
        return $this;
    }

    public function getShell(): string
    {
        return $this->Shell;
    }

    public function setShell(string $shell): self
    {
        $this->Shell = $shell;
        return $this;
    }

    public function getInterval(): string
    {
        return $this->Interval;
    }

    public function setInterval(string $interval): self
    {
        $this->Interval = $interval;
        return $this;
    }

    public function getTimeout(): string
    {
        return $this->Timeout;
    }

    public function setTimeout(string $timeout): self
    {
        $this->Timeout = $timeout;
        return $this;
    }

    public function getTTL(): string
    {
        return $this->TTL;
    }

    public function setTTL(string $ttl): self
    {
        $this->TTL = $ttl;
        return $this;
    }

    public function getHTTP(): string
    {
        return $this->HTTP;
    }

    public function setHTTP(string $http): self
    {
        $this->HTTP = $http;
        return $this;
    }

    public function getHeader(): array
    {
        return $this->Header;
    }

    public function setHeader(array $header): self
    {
        $this->Header = $header;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->Method;
    }

    public function setMethod(string $method): self
    {
        $this->Method = $method;
        return $this;
    }

    public function getTCP(): string
    {
        return $this->TCP;
    }

    public function setTCP(string $tcp): self
    {
        $this->TCP = $tcp;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $status): self
    {
        $this->Status = $status;
        return $this;
    }

    public function getNotes(): string
    {
        return $this->Notes;
    }

    public function setNotes(string $notes): self
    {
        $this->Notes = $notes;
        return $this;
    }

    public function isTLSSkipVerify(): bool
    {
        return $this->TLSSkipVerify;
    }

    public function setTLSSkipVerify(bool $tlsSkipVerify): self
    {
        $this->TLSSkipVerify = $tlsSkipVerify;
        return $this;
    }

    public function getGRPC(): string
    {
        return $this->GRPC;
    }

    public function setGRPC(string $GRPC): self
    {
        $this->GRPC = $GRPC;
        return $this;
    }

    public function isGRPCUseTLS(): bool
    {
        return $this->GRPCUseTLS;
    }

    public function setGRPCUseTLS(bool $GRPCUseTLS): self
    {
        $this->GRPCUseTLS = $GRPCUseTLS;
        return $this;
    }

    public function getAliasNode(): string
    {
        return $this->AliasNode;
    }

    public function setAliasNode(string $AliasNode): self
    {
        $this->AliasNode = $AliasNode;
        return $this;
    }

    public function getAliasService(): string
    {
        return $this->AliasService;
    }

    public function setAliasService(string $AliasService): self
    {
        $this->AliasService = $AliasService;
        return $this;
    }

    public function getSuccessBeforePassing(): int
    {
        return $this->SuccessBeforePassing;
    }

    public function setSuccessBeforePassing(int $SuccessBeforePassing): self
    {
        $this->SuccessBeforePassing = $SuccessBeforePassing;
        return $this;
    }

    public function getFailuresBeforeCritical(): int
    {
        return $this->FailuresBeforeCritical;
    }

    public function setFailuresBeforeCritical(int $FailuresBeforeCritical): self
    {
        $this->FailuresBeforeCritical = $FailuresBeforeCritical;
        return $this;
    }

    public function getDeregisterCriticalServiceAfter(): string
    {
        return $this->DeregisterCriticalServiceAfter;
    }

    public function setDeregisterCriticalServiceAfter(string $deregisterCriticalServiceAfter): self
    {
        $this->DeregisterCriticalServiceAfter = $deregisterCriticalServiceAfter;
        return $this;
    }
}
