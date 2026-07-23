<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;
use DCarbone\PHPConsulAPI\PHPLib\Values;

class AgentServiceCheck extends AbstractType
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
    public null|Values $Header = null;
    public string $Method;
    public string $Body;
    public string $TCP;
    public bool $TCPUseTLS;
    public string $UDP;
    public string $Status;
    public string $Notes;
    public string $TLSServerName;
    public bool $TLSSkipVerify;
    public string $GRPC;
    public bool $GRPCUseTLS;
    public string $H2PING;
    public bool $H2PINGUseTLS;
    public string $AliasNode;
    public string $AliasService;
    public int $SuccessBeforePassing;
    public int $FailuresBeforeWarning;
    public int $FailuresBeforeCritical;
    public string $DeregisterCriticalServiceAfter;

    /**
     * @param array<string> $Args
     * @param null|array<string,array<string>>|\DCarbone\PHPConsulAPI\PHPLib\Values $Header
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $CheckID = '',
        string $Name = '',
        array $Args = [],
        string $DockerContainerID = '',
        string $Shell = '',
        string $Interval = '',
        string $Timeout = '',
        string $TTL = '',
        string $HTTP = '',
        null|array|Values $Header = null,
        string $Method = '',
        string $Body = '',
        string $TCP = '',
        bool $TCPUseTLS = false,
        string $UDP = '',
        string $Status = '',
        string $Notes = '',
        string $TLSServerName = '',
        bool $TLSSkipVerify = false,
        string $GRPC = '',
        bool $GRPCUseTLS = false,
        string $H2PING = '',
        bool $H2PINGUseTLS = false,
        string $AliasNode = '',
        string $AliasService = '',
        int $SuccessBeforePassing = 0,
        int $FailuresBeforeWarning = 0,
        int $FailuresBeforeCritical = 0,
        string $DeregisterCriticalServiceAfter = '',
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
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
        $this->setHeader($Header);
        $this->Method = $Method;
        $this->Body = $Body;
        $this->TCP = $TCP;
        $this->TCPUseTLS = $TCPUseTLS;
        $this->UDP = $UDP;
        $this->Status = $Status;
        $this->Notes = $Notes;
        $this->TLSServerName = $TLSServerName;
        $this->TLSSkipVerify = $TLSSkipVerify;
        $this->GRPC = $GRPC;
        $this->GRPCUseTLS = $GRPCUseTLS;
        $this->H2PING = $H2PING;
        $this->H2PINGUseTLS = $H2PINGUseTLS;
        $this->AliasNode = $AliasNode;
        $this->AliasService = $AliasService;
        $this->SuccessBeforePassing = $SuccessBeforePassing;
        $this->FailuresBeforeWarning = $FailuresBeforeWarning;
        $this->FailuresBeforeCritical = $FailuresBeforeCritical;
        $this->DeregisterCriticalServiceAfter = $DeregisterCriticalServiceAfter;
    }

    public function getCheckID(): string
    {
        return $this->CheckID;
    }

    public function setCheckID(string $CheckID): self
    {
        $this->CheckID = $CheckID;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getArgs(): array
    {
        return $this->Args;
    }

    public function setArgs(string ...$Args): self
    {
        $this->Args = $Args;
        return $this;
    }

    public function getDockerContainerID(): string
    {
        return $this->DockerContainerID;
    }

    public function setDockerContainerID(string $DockerContainerID): self
    {
        $this->DockerContainerID = $DockerContainerID;
        return $this;
    }

    public function getShell(): string
    {
        return $this->Shell;
    }

    public function setShell(string $Shell): self
    {
        $this->Shell = $Shell;
        return $this;
    }

    public function getInterval(): string
    {
        return $this->Interval;
    }

    public function setInterval(string $Interval): self
    {
        $this->Interval = $Interval;
        return $this;
    }

    public function getTimeout(): string
    {
        return $this->Timeout;
    }

    public function setTimeout(string $Timeout): self
    {
        $this->Timeout = $Timeout;
        return $this;
    }

    public function getTTL(): string
    {
        return $this->TTL;
    }

    public function setTTL(string $TTL): self
    {
        $this->TTL = $TTL;
        return $this;
    }

    public function getHTTP(): string
    {
        return $this->HTTP;
    }

    public function setHTTP(string $HTTP): self
    {
        $this->HTTP = $HTTP;
        return $this;
    }

    public function getHeader(): null|Values
    {
        return $this->Header;
    }

    /**
     * @param \stdClass|array<string,array<string>>|\DCarbone\PHPConsulAPI\PHPLib\Values|null $Header
     * @return $this
     */
    public function setHeader(null|\stdClass|array|Values $Header): self
    {
        if (null === $Header) {
            $this->Header = null;
            return $this;
        }
        if (!$Header instanceof Values) {
            $Header = Values::fromArray((array)$Header);
        }
        $this->Header = $Header;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->Method;
    }

    public function setMethod(string $Method): self
    {
        $this->Method = $Method;
        return $this;
    }

    public function getBody(): string
    {
        return $this->Body;
    }

    public function setBody(string $Body): self
    {
        $this->Body = $Body;
        return $this;
    }

    public function getTCP(): string
    {
        return $this->TCP;
    }

    public function setTCP(string $TCP): self
    {
        $this->TCP = $TCP;
        return $this;
    }

    public function isTCPUseTLS(): bool
    {
        return $this->TCPUseTLS;
    }

    public function setTCPUseTLS(bool $TCPUseTLS): self
    {
        $this->TCPUseTLS = $TCPUseTLS;
        return $this;
    }

    public function getUDP(): string
    {
        return $this->UDP;
    }

    public function setUDP(string $UDP): self
    {
        $this->UDP = $UDP;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;
        return $this;
    }

    public function getNotes(): string
    {
        return $this->Notes;
    }

    public function setNotes(string $Notes): self
    {
        $this->Notes = $Notes;
        return $this;
    }

    public function getTLSServerName(): string
    {
        return $this->TLSServerName;
    }

    public function setTLSServerName(string $TLSServerName): self
    {
        $this->TLSServerName = $TLSServerName;
        return $this;
    }

    public function isTLSSkipVerify(): bool
    {
        return $this->TLSSkipVerify;
    }

    public function setTLSSkipVerify(bool $TLSSkipVerify): self
    {
        $this->TLSSkipVerify = $TLSSkipVerify;
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

    public function setH2PING(string $H2PING): self
    {
        $this->H2PING = $H2PING;
        return $this;
    }

    public function isH2PINGUseTLS(): bool
    {
        return $this->H2PINGUseTLS;
    }

    public function setH2PINGUseTLS(bool $H2PINGUseTLS): self
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

    public function getFailuresBeforeWarning(): int
    {
        return $this->FailuresBeforeWarning;
    }

    public function setFailuresBeforeWarning(int $FailuresBeforeWarning): self
    {
        $this->FailuresBeforeWarning = $FailuresBeforeWarning;
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

    public function setDeregisterCriticalServiceAfter(string $DeregisterCriticalServiceAfter): self
    {
        $this->DeregisterCriticalServiceAfter = $DeregisterCriticalServiceAfter;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('ScriptArgs' === $k) {
                $n->Args = $v;
            } elseif ('Header' === $k) {
                $n->setHeader($v);
            } else {
                $n->{$k} = $v;
            }
        }
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
        if ('' !== $this->Body) {
            $out->Body = $this->Body;
        }
        if ('' !== $this->TCP) {
            $out->TCP = $this->TCP;
        }
        if ($this->TCPUseTLS) {
            $out->TCPUseTLS = $this->TCPUseTLS;
        }
        if ('' !== $this->UDP) {
            $out->UDP = $this->UDP;
        }
        if ('' !== $this->Status) {
            $out->Status = $this->Status;
        }
        if ('' !== $this->Notes) {
            $out->Notes = $this->Notes;
        }
        if ('' !== $this->TLSServerName) {
            $out->TLSServerName = $this->TLSServerName;
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
        if (0 !== $this->FailuresBeforeWarning) {
            $out->FailuresBeforeWarning = $this->FailuresBeforeWarning;
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
