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

class AgentMember extends AbstractModel
{
    public string $Name;
    public string $Addr;
    public int $Port;
    public null|\stdClass $Tags;
    /**
     * Status of the Member which corresponds to  github.com/hashicorp/serf/serf.MemberStatus
     * Value is one of:
     *      AgentMemberNone    = 0
     *      AgentMemberAlive   = 1
     *      AgentMemberLeaving = 2
     *      AgentMemberLeft    = 3
     *      AgentMemberFailed  = 4
     * @var int
     */
    public int $Status;
    public int $ProtocolMin;
    public int $ProtocolMax;
    public int $ProtocolCur;
    public int $DelegateMin;
    public int $DelegateMax;
    public int $DelegateCur;

    /**
     * @param string $Name
     * @param string $Addr
     * @param int $Port
     * @param \stdClass|null $Tags
     * @param int $Status
     * @param int $ProtocolMin
     * @param int $ProtocolMax
     * @param int $ProtocolCur
     * @param int $DelegateMin
     * @param int $DelegateMax
     * @param int $DelegateCur
     */
    public function __construct(
        string $Name = '',
        string $Addr = '',
        int $Port = 0,
        null|\stdClass $Tags = null,
        int $Status = 0,
        int $ProtocolMin = 0,
        int $ProtocolMax = 0,
        int $ProtocolCur = 0,
        int $DelegateMin = 0,
        int $DelegateMax = 0,
        int $DelegateCur = 0,
    ) {
        $this->Name = $Name;
        $this->Addr = $Addr;
        $this->Port = $Port;
        $this->Tags = $Tags;
        $this->Status = $Status;
        $this->ProtocolMin = $ProtocolMin;
        $this->ProtocolMax = $ProtocolMax;
        $this->ProtocolCur = $ProtocolCur;
        $this->DelegateMin = $DelegateMin;
        $this->DelegateMax = $DelegateMax;
        $this->DelegateCur = $DelegateCur;
}

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

    public function getStatus(): int
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

    public function ACLMode(): MemberACLMode
    {
        return match ($this->Tags[Consul::MemberTagKeyACLMode] ?? null) {
            MemberACLMode::Disabled->value => MemberACLMode::Disabled,
            MemberACLMode::Enabled->value => MemberACLMode::Enabled,
            MemberACLMode::Legacy->value => MemberACLMode::Legacy,
            default => MemberACLMode::Unknown,
        };
    }

    public function IsConsulServer(): bool
    {
        return isset($this->Tags[Consul::MemberTagKeyACLMode]) &&
            Consul::MemberTagValueRoleServer === $this->Tags[Consul::MemberTagKeyACLMode];
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Name = $this->Name;
        $out->Addr = $this->Addr;
        $out->Port = $this->Port;
        $out->Tags = $this->Tags;
        $out->Status = $this->Status;
        $out->ProtocolMin = $this->ProtocolMin;
        $out->ProtocolMax = $this->ProtocolMax;
        $out->ProtocolCur = $this->ProtocolCur;
        $out->DelegateMin = $this->DelegateMin;
        $out->DelegateMax = $this->DelegateMax;
        $out->DelegateCur = $this->DelegateCur;
        return $out;
    }

    public function __toString(): string
    {
        return $this->Name;
    }
}
