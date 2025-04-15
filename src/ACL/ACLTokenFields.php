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

trait ACLTokenFields
{
    public int $CreateIndex;
    public int $ModifyIndex;
    public string $AccessorID;
    public string $SecretID;
    public string $Description;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink[] */
    public array $Policies;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink[] */
    public array $Roles;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity[] */
    public array $ServiceIdentities;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity[] */
    public array $NodeIdentities;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy[] */
    public array $TemplatePolicies;
    public bool $Local;
    public string $AuthMethod;
    public Time\Duration $ExpirationTTL;
    public null|Time\Time $ExpirationTime = null;
    public Time\Time $CreateTime;
    public string $Hash;
    public string $Namespace;
    public string $Partition;
    public string $AuthMethodNamespace;

    public string $Rules;

    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    public function getAccessorID(): string
    {
        return $this->AccessorID;
    }

    public function setAccessorID(string $AccessorID): self
    {
        $this->AccessorID = $AccessorID;
        return $this;
    }

    public function getSecretID(): string
    {
        return $this->SecretID;
    }

    public function setSecretID(string $SecretID): self
    {
        $this->SecretID = $SecretID;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink[]
     */
    public function getPolicies(): array
    {
        return $this->Policies;
    }

    public function setPolicies(ACLTokenPolicyLink ...$Policies): self
    {
        $this->Policies = $Policies;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink[]
     */
    public function getRoles(): array
    {
        return $this->Roles;
    }

    public function setRoles(ACLTokenRoleLink ...$Roles): self
    {
        $this->Roles = $Roles;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity[]
     */
    public function getServiceIdentities(): array
    {
        return $this->ServiceIdentities;
    }

    public function setServiceIdentities(ACLServiceIdentity ...$ServiceIdentities): self
    {
        $this->ServiceIdentities = $ServiceIdentities;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity[]
     */
    public function getNodeIdentities(): array
    {
        return $this->NodeIdentities;
    }

    public function setNodeIdentities(ACLNodeIdentity ...$NodeIdentities): self
    {
        $this->NodeIdentities = $NodeIdentities;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy[]
     */
    public function getTemplatePolicies(): array
    {
        return $this->TemplatePolicies;
    }

    public function setTemplatePolicies(ACLTemplatedPolicy ...$TemplatePolicies): self
    {
        $this->TemplatePolicies = $TemplatePolicies;
        return $this;
    }

    public function isLocal(): bool
    {
        return $this->Local;
    }

    public function setLocal(bool $Local): self
    {
        $this->Local = $Local;
        return $this;
    }

    public function getAuthMethod(): string
    {
        return $this->AuthMethod;
    }

    public function setAuthMethod(string $AuthMethod): self
    {
        $this->AuthMethod = $AuthMethod;
        return $this;
    }

    public function getExpirationTTL(): Time\Duration
    {
        return $this->ExpirationTTL;
    }

    public function setExpirationTTL(null|int|float|string|\DateInterval|Time\Duration $ExpirationTTL): self
    {
        $this->ExpirationTTL = Time::Duration($ExpirationTTL);
        return $this;
    }

    public function getExpirationTime(): Time\Time
    {
        return $this->ExpirationTime;
    }

    public function setExpirationTime(null|Time\Time $ExpirationTime): self
    {
        $this->ExpirationTime = $ExpirationTime;
        return $this;
    }

    public function getCreateTime(): Time\Time
    {
        return $this->CreateTime;
    }

    public function setCreateTime(Time\Time $CreateTime): self
    {
        $this->CreateTime = $CreateTime;
        return $this;
    }

    public function getHash(): string
    {
        return $this->Hash;
    }

    public function setHash(string $Hash): self
    {
        $this->Hash = $Hash;
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

    public function getRules(): string
    {
        return $this->Rules;
    }

    public function setRules(string $Rules): self
    {
        $this->Rules = $Rules;
        return $this;
    }

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public function getAuthMethodNamespace(): string
    {
        return $this->AuthMethodNamespace;
    }

    public function setAuthMethodNamespace(string $AuthMethodNamespace): self
    {
        $this->AuthMethodNamespace = $AuthMethodNamespace;
        return $this;
    }

    public function _jsonUnserializeField(string $k, mixed $v, object $n): bool
    {
        if ('Policies' === $k) {
            foreach ($v as $vv) {
                $n->Policies[] = ACLTokenPolicyLink::jsonUnserialize($vv);
            }
        } elseif ('Roles' === $k) {
            foreach ($v as $vv) {
                $n->Roles[] = ACLTokenRoleLink::jsonUnserialize($vv);
            }
        } elseif ('ServiceIdentities' === $k) {
            foreach ($v as $vv) {
                $n->ServiceIdentities[] = ACLServiceIdentity::jsonUnserialize($vv);
            }
        } elseif ('NodeIdentities' === $k) {
            foreach ($v as $vv) {
                $n->NodeIdentities[] = ACLNodeIdentity::jsonUnserialize($vv);
            }
        } elseif ('TemplatePolicies' === $k) {
            foreach ($v as $vv) {
                $n->TemplatePolicies[] = ACLTemplatedPolicy::jsonUnserialize($vv);
            }
        } elseif ('ExpirationTTL' === $k) {
            $n->setExpirationTTL($v);
        } elseif ('ExpirationTime' === $k) {
            $n->ExpirationTime = (null === $v ? $v : Time\Time::createFromFormat(DATE_RFC3339, $v));
        } elseif ('CreateTime' === $k) {
            $n->CreateTime = Time\Time::createFromFormat(DATE_RFC3339, $v);
        } else {
            return false;
        }

        return true;
    }

    protected function _jsonSerialize(\stdClass $out): void
    {
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        $out->AccessorID = $this->AccessorID;
        $out->SecretID = $this->SecretID;
        $out->Description = $this->Description;
        if ([] !== $this->Policies) {
            $out->Policies = $this->Policies;
        }
        if ([] !== $this->Roles) {
            $out->Roles = $this->Roles;
        }
        if ([] !== $this->ServiceIdentities) {
            $out->ServiceIdentities = $this->ServiceIdentities;
        }
        if ([] !== $this->NodeIdentities) {
            $out->NodeIdentities = $this->NodeIdentities;
        }
        if ([] !== $this->TemplatePolicies) {
            $out->TemplatePolicies = $this->TemplatePolicies;
        }
        $out->Local = $this->Local;
        if ('' !== $this->AuthMethod) {
            $out->AuthMethod = $this->AuthMethod;
        }
        if (0 !== $this->ExpirationTTL->Nanoseconds()) {
            $out->ExpirationTTL = $this->ExpirationTTL;
        }
        if (null !== $this->ExpirationTime) {
            $out->ExpirationTime = $this->ExpirationTime->format(DATE_RFC3339);
        }
        if (!$this->CreateTime->isZero()) {
            $out->CreateTime = $this->CreateTime->format(DATE_RFC3339);
        }
        $out->Hash = $this->Hash;
        if ('' !== $this->Rules) {
            $out->Rules = $this->Rules;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->AuthMethodNamespace) {
            $out->AuthMethodNamespace = $this->AuthMethodNamespace;
        }
    }
}
