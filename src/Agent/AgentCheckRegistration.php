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

class AgentCheckRegistration extends AgentServiceCheck
{
    public string $ID;
    public string $ServiceID;
    public string $Namespace;
    public string $Partition;

    public function __construct(
        string $ID = '',
        string $ServiceID = '',
        string $CheckID = '',
        string $Name = '',
        iterable $ScriptArgs = [],
        string $DockerContainerID = '',
        string $Shell = '',
        string $Interval = '',
        string $Timeout = '',
        string $TTL = '',
        string $HTTP = '',
        iterable $Header = [],
        string $Method = '',
        string $TCP = '',
        string $Status = '',
        string $Notes = '',
        bool $TLSSkipVerify = false,
        string $GRPC = '',
        bool $GRPCUseTLS = false,
        string $H2PING = '',
        bool $H2PingUseTLS = false,
        string $AliasNode = '',
        string $AliasService = '',
        int $SuccessBeforePassing = 0,
        int $FailuresBeforeCritical = 0,
        string $DeregisterCriticalServiceAfter = '',
        string $Namespace = '',
        string $Partition = '',
    ) {
        parent::__construct(
            CheckID: $CheckID,
            Name: $Name,
            ScriptArgs: $ScriptArgs,
            DockerContainerID: $DockerContainerID,
            Shell: $Shell,
            Interval: $Interval,
            Timeout: $Timeout,
            TTL: $TTL,
            HTTP: $HTTP,
            Header: $Header,
            Method: $Method,
            TCP: $TCP,
            Status: $Status,
            Notes: $Notes,
            TLSSkipVerify: $TLSSkipVerify,
            GRPC: $GRPC,
            GRPCUseTLS: $GRPCUseTLS,
            H2PING: $H2PING,
            H2PINGUseTLS: $H2PingUseTLS,
            AliasNode: $AliasNode,
            AliasService: $AliasService,
            SuccessBeforePassing: $SuccessBeforePassing,
            FailuresBeforeCritical: $FailuresBeforeCritical,
            DeregisterCriticalServiceAfter: $DeregisterCriticalServiceAfter,
        );
        $this->ID = $ID;
        $this->ServiceID = $ServiceID;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
}

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    public function setServiceID(string $ServiceID): self
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = parent::jsonSerialize();
        if ('' !== $this->ID) {
            $out->ID = $this->ID;
        }
        if ('' !== $this->ServiceID) {
            $out->ServiceID = $this->ServiceID;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if (isset($out->Name) && $out->Name === '') {
            unset($out->Name);
        }
        if (isset($out->Notes) && $out->Notes === '') {
            unset($out->Notes);
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        return $out;
    }
}
