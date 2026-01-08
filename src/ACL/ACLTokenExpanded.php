<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\Go\Time;

class ACLTokenExpanded extends ACLToken
{
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLPolicy[] */
    public array $ExpandedPolicies;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLRole[] */
    public array $ExpandedRoles;
    /** @var string[] */
    public array $NamespaceDefaultPolicyIDs;
    /** @var string[] */
    public array $NamespaceDefaultRoleIDs;
    public string $AgentACLDefaultPolicy;
    public string $AgentACLDownPolicy;
    public string $ResolvedByAgent;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLPolicy> $ExpandedPolicies
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLRole> $ExpandedRoles
     * @param array<string> $NamespaceDefaultPolicyIDs
     * @param array<string> $NamespaceDefaultRoleIDs
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink> $Policies
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink> $Roles
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity> $ServiceIdentities
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity> $NodeIdentities
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy> $TemplatePolicies
     */
    public function __construct(
        array $ExpandedPolicies = [],
        array $ExpandedRoles = [],
        array $NamespaceDefaultPolicyIDs = [],
        array $NamespaceDefaultRoleIDs = [],
        string $AgentACLDefaultPolicy = '',
        string $AgentACLDownPolicy = '',
        string $ResolvedByAgent = '',
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        string $AccessorID = '',
        string $SecretID = '',
        string $Description = '',
        array $Policies = [],
        array $Roles = [],
        array $ServiceIdentities = [],
        array $NodeIdentities = [],
        array $TemplatePolicies = [],
        bool $Local = false,
        string $AuthMethod = '',
        \DateInterval|float|int|string|Time\Duration|null $ExpirationTTL = null,
        null|Time\Time $ExpirationTime = null,
        null|Time\Time $CreateTime = null,
        string $Hash = '',
        string $Namespace = '',
        string $Rules = '',
        string $Partition = '',
        string $AuthMethodNamespace = ''
    ) {
        parent::__construct(
            CreateIndex: $CreateIndex,
            ModifyIndex: $ModifyIndex,
            AccessorID: $AccessorID,
            SecretID: $SecretID,
            Description: $Description,
            Policies: $Policies,
            Roles: $Roles,
            ServiceIdentities: $ServiceIdentities,
            NodeIdentities: $NodeIdentities,
            TemplatePolicies: $TemplatePolicies,
            Local: $Local,
            AuthMethod: $AuthMethod,
            ExpirationTTL: $ExpirationTTL,
            ExpirationTime: $ExpirationTime,
            CreateTime: $CreateTime,
            Hash: $Hash,
            Namespace: $Namespace,
            Rules: $Rules,
            Partition: $Partition,
            AuthMethodNamespace: $AuthMethodNamespace
        );
        $this->setExpandedPolicies(...$ExpandedPolicies);
        $this->setExpandedRoles(...$ExpandedRoles);
        $this->setNamespaceDefaultPolicyIDs(...$NamespaceDefaultPolicyIDs);
        $this->setNamespaceDefaultRoleIDs(...$NamespaceDefaultRoleIDs);
        $this->AgentACLDefaultPolicy = $AgentACLDefaultPolicy;
        $this->AgentACLDownPolicy = $AgentACLDownPolicy;
        $this->ResolvedByAgent = $ResolvedByAgent;
}

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLPolicy[]
     */
    public function getExpandedPolicies(): array
    {
        return $this->ExpandedPolicies;
    }

    public function setExpandedPolicies(ACLPolicy ...$ExpandedPolicies): self
    {
        $this->ExpandedPolicies = $ExpandedPolicies;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ACL\ACLRole>
     */
    public function getExpandedRoles(): array
    {
        return $this->ExpandedRoles;
    }

    public function setExpandedRoles(ACLRole ...$ExpandedRoles): self
    {
        $this->ExpandedRoles = $ExpandedRoles;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getNamespaceDefaultPolicyIDs(): array
    {
        return $this->NamespaceDefaultPolicyIDs;
    }

    public function setNamespaceDefaultPolicyIDs(string ...$NamespaceDefaultPolicyIDs): self
    {
        $this->NamespaceDefaultPolicyIDs = $NamespaceDefaultPolicyIDs;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getNamespaceDefaultRoleIDs(): array
    {
        return $this->NamespaceDefaultRoleIDs;
    }

    public function setNamespaceDefaultRoleIDs(string ...$NamespaceDefaultRoleIDs): self
    {
        $this->NamespaceDefaultRoleIDs = $NamespaceDefaultRoleIDs;
        return $this;
    }

    public function getAgentACLDefaultPolicy(): string
    {
        return $this->AgentACLDefaultPolicy;
    }

    public function setAgentACLDefaultPolicy(string $AgentACLDefaultPolicy): self
    {
        $this->AgentACLDefaultPolicy = $AgentACLDefaultPolicy;
        return $this;
    }

    public function getAgentACLDownPolicy(): string
    {
        return $this->AgentACLDownPolicy;
    }

    public function setAgentACLDownPolicy(string $AgentACLDownPolicy): self
    {
        $this->AgentACLDownPolicy = $AgentACLDownPolicy;
        return $this;
    }

    public function getResolvedByAgent(): string
    {
        return $this->ResolvedByAgent;
    }

    public function setResolvedByAgent(string $ResolvedByAgent): self
    {
        $this->ResolvedByAgent = $ResolvedByAgent;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|ACLToken $into = null): static
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ($n->_jsonUnserializeField($k, $v, $n)) {
                continue;
            }

            if ('ExpandedPolicies' === $k) {
                foreach ($v as $vv) {
                    $n->ExpandedPolicies[] = ACLPolicy::jsonUnserialize($vv);
                }
            } elseif ('ExpandedRoles' === $k) {
                foreach ($v as $vv) {
                    $n->ExpandedRoles[] = ACLRole::jsonUnserialize($vv);
                }
            } elseif ('NamespaceDefaultPolicyIDs' === $k) {
                $n->setNamespaceDefaultPolicyIDs(...$v);
            } elseif ('NamespaceDefaultRoleIDs' === $k) {
                $n->setNamespaceDefaultRoleIDs(...$v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = parent::jsonSerialize();

        $out->ExpandedPolicies = $this->ExpandedPolicies;
        $out->ExpandedRoles = $this->ExpandedRoles;
        $out->NamespaceDefaultPolicyIDs = $this->NamespaceDefaultPolicyIDs;
        $out->NamespaceDefaultRoleIDs = $this->NamespaceDefaultRoleIDs;
        $out->AgentACLDefaultPolicy = $this->AgentACLDefaultPolicy;
        $out->AgentACLDownPolicy = $this->AgentACLDownPolicy;
        $out->ResolvedByAgent = $this->ResolvedByAgent;

        return $out;
    }
}
