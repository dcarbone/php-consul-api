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
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\HasStringTags;

class AgentMember extends AbstractModel
{
    use HasStringTags;

    public string $Name;
    public string $Addr;
    public int $Port;
    public string $Status;
    public int $ProtocolMin;
    public int $ProtocolMax;
    public int $ProtocolCur;
    public int $DelegateMin;
    public int $DelegateMax;
    public int $DelegateCur;

    public function getName(): string
    {
        return $this->Name;
    }

    public function getAddr(): string
    {
        return $this->Addr;
    }

    public function getPort(): int
    {
        return $this->Port;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function getProtocolMin(): int
    {
        return $this->ProtocolMin;
    }

    public function getProtocolMax(): int
    {
        return $this->ProtocolMax;
    }

    public function getProtocolCur(): int
    {
        return $this->ProtocolCur;
    }

    public function getDelegateMin(): int
    {
        return $this->DelegateMin;
    }

    public function getDelegateMax(): int
    {
        return $this->DelegateMax;
    }

    public function getDelegateCur(): int
    {
        return $this->DelegateCur;
    }

    public function ACLMode(): string
    {
        return match ($this->Tags[Consul::MemberTagKeyACLMode] ?? null) {
            Consul::ACLModeDisabled => Consul::ACLModeDisabled,
            Consul::ACLModeEnabled => Consul::ACLModeEnabled,
            Consul::ACLModeLegacy => Consul::ACLModeLegacy,
            default => Consul::ACLModeUnknown,
        };
    }

    public function IsConsulServer(): bool
    {
        return isset($this->Tags[Consul::MemberTagKeyACLMode]) &&
            Consul::MemberTagValueRoleServer === $this->Tags[Consul::MemberTagKeyACLMode];
    }

    public function __toString(): string
    {
        return $this->Name;
    }
}
