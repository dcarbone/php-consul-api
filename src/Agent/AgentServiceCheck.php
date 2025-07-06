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

class AgentServiceCheck extends AbstractModel
{
    public string $CheckID;
    public string $Name;
    /** @var string[] */
    public array $Args;
    public string $DockerContainerID;
    public string $Shell;
    public string $Interval;
    public string $Timeout;
    public string $TTL;
    public string $HTTP;
    public null|\stdClass $Header;
    public string $Method;
    public string $TCP;
    public string $Status;
    public string $Notes;
    public bool $TLSSkipVerify;
    public string $GRPC;
    public bool $GRPCUseTLS;
    public string $H2PING;
    public bool $H2PINGUseTLS;
    public string $AliasNode;
    public string $AliasService;
    public int $SuccessBeforePassing;
    public int $FailuresBeforeCritical;
    public string $DeregisterCriticalServiceAfter;

    /**
     * @param array<string> $Args
     */
    public function __construct(
        string         $CheckID = '',
        string         $Name = '',
        array          $Args = [],
        string         $DockerContainerID = '',
        string         $Shell = '',
        string         $Interval = '',
        string         $Timeout = '',
        string         $TTL = '',
        string         $HTTP = '',
        null|\stdClass $Header = null,
        string         $Method = '',
        string         $TCP = '',
        string         $Status = '',
        string $Notes = '',
        bool $TLSSkipVerify = false,
        string $GRPC = '',
        bool $GRPCUseTLS = false,
        string $H2PING = '',
        bool $H2PINGUseTLS = false,
        string $AliasNode = '',
        string $AliasService = '',
        int $SuccessBeforePassing = 0,
        int $FailuresBeforeCritical = 0,
        string $DeregisterCriticalServiceAfter = '',
    ) {
        $this->CheckID = $CheckID;
        $this->Name = $Name;
        $this->Args = [];
        $this->setArgs(...$Args);
        $this->DockerContainerID = $DockerContainerID;
        $this->Shell = $Shell;
        $this->Interval = $Interval;
        $this->Timeout = $Timeout;
        $this->TTL = $TTL;
        $this->HTTP = $HTTP;
        $this->Header = $Header;
        $this->Method = $Method;
        $this->TCP = $TCP;
        $this->Status = $Status;
        $this->Notes = $Notes;
        $this->TLSSkipVerify = $TLSSkipVerify;
        $this->GRPC = $GRPC;
        $this->GRPCUseTLS = $GRPCUseTLS;
        $this->H2PING = $H2PING;
        $this->H2PINGUseTLS = $H2PINGUseTLS;
        $this->AliasNode = $AliasNode;
        $this->AliasService = $AliasService;
        $this->SuccessBeforePassing = $SuccessBeforePassing;
        $this->FailuresBeforeCritical = $FailuresBeforeCritical;
        $this->DeregisterCriticalServiceAfter = $DeregisterCriticalServiceAfter;
}

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

    /**
     * @return array<string>
     */
    public function getArgs(): array
    {
        return $this->Args;
    }

    public function setArgs(string ...$args): self
    {
        $this->Args = $args;
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

    public function getHeader(): null|\stdClass
    {
        return $this->Header;
    }

    public function setHeader(null|\stdClass $Header): self
    {
        $this->Header = $Header;
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

    public function getH2PING(): string
    {
        return $this->H2PING;
    }

    public function setH2PING(string $H2PING): AgentServiceCheck
    {
        $this->H2PING = $H2PING;
        return $this;
    }

    public function isH2PINGUseTLS(): bool
    {
        return $this->H2PINGUseTLS;
    }

    public function setH2PINGUseTLS(bool $H2PINGUseTLS): AgentServiceCheck
    {
        $this->H2PINGUseTLS = $H2PINGUseTLS;
        return $this;
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('ScriptArgs' === $k) {
                $n->Args = $v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ('' !== $this->CheckID) {
            $out->CheckID = $this->CheckID;
        }
        if ('' !== $this->Name) {
            $out->Name = $this->Name;
        }
        if ([] !== $this->Args) {
            $out->ScriptArgs = $this->Args;
        }
        if ('' !== $this->DockerContainerID) {
            $out->DockerContainerID = $this->DockerContainerID;
        }
        if ('' !== $this->Shell) {
            $out->Shell = $this->Shell;
        }
        if ('' !== $this->Interval) {
            $out->Interval = $this->Interval;
        }
        if ('' !== $this->Timeout) {
            $out->Timeout = $this->Timeout;
        }
        if ('' !== $this->TTL) {
            $out->TTL = $this->TTL;
        }
        if ('' !== $this->HTTP) {
            $out->HTTP = $this->HTTP;
        }
        if (null !== $this->Header) {
            $out->Header = $this->Header;
        }
        if ('' !== $this->Method) {
            $out->Method = $this->Method;
        }
        if ('' !== $this->TCP) {
            $out->TCP = $this->TCP;
        }
        if ('' !== $this->Status) {
            $out->Status = $this->Status;
        }
        if ('' !== $this->Notes) {
            $out->Notes = $this->Notes;
        }
        if ($this->TLSSkipVerify) {
            $out->TLSSkipVerify = $this->TLSSkipVerify;
        }
        if ('' !== $this->GRPC) {
            $out->GRPC = $this->GRPC;
        }
        if ($this->GRPCUseTLS) {
            $out->GRPCUseTLS = $this->GRPCUseTLS;
        }
        if ('' !== $this->H2PING) {
            $out->H2PING = $this->H2PING;
        }
        if ($this->H2PINGUseTLS) {
            $out->H2PINGUseTLS = $this->H2PINGUseTLS;
        }
        if ('' !== $this->AliasNode) {
            $out->AliasNode = $this->AliasNode;
        }
        if ('' !== $this->AliasService) {
            $out->AliasService = $this->AliasService;
        }
        if (0 !== $this->SuccessBeforePassing) {
            $out->SuccessBeforePassing = $this->SuccessBeforePassing;
        }
        if (0 !== $this->FailuresBeforeCritical) {
            $out->FailuresBeforeCritical = $this->FailuresBeforeCritical;
        }
        if ('' !== $this->DeregisterCriticalServiceAfter) {
            $out->DeregisterCriticalServiceAfter = $this->DeregisterCriticalServiceAfter;
        }
        return $out;
    }
}
