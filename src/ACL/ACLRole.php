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

use DCarbone\PHPConsulAPI\AbstractModel;

class ACLRole extends AbstractModel
{
    public string $ID;
    public string $Name;
    public string $Description;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLRolePolicyLink[] */
    public array $Policies;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity[] */
    public array $ServiceIdentities;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity[] */
    public array $NodeIdentities;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy[] */
    public array $TemplatedPolicies;
    public string $Hash;
    public int $CreateIndex;
    public int $ModifyIndex;
    public string $Namespace;
    public string $Partition;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $ID = '',
        string $Name = '',
        string $Description = '',
        iterable $Policies = [],
        iterable $ServiceIdentities = [],
        iterable $NodeIdentities = [],
        iterable $TemplatedPolicies = [],
        string $Hash = '',
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        string $Namespace = '',
        string $Partition = '',
    ) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Description = $Description;
        $this->setPolicies(...$Policies);
        $this->setServiceIdentities(...$ServiceIdentities);
        $this->setNodeIdentities(...$NodeIdentities);
        $this->setTemplatedPolicies(...$TemplatedPolicies);
        $this->Hash = $Hash;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
        }
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

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRolePolicyLink[]
     */
    public function getPolicies(): array
    {
        return $this->Policies;
    }

    public function setPolicies(ACLRolePolicyLink ...$Policies): self
    {
        $this->Policies = $Policies;
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
    public function getTemplatedPolicies(): array
    {
        return $this->TemplatedPolicies;
    }

    public function setTemplatedPolicies(ACLTemplatedPolicy ...$TemplatedPolicies): self
    {
        $this->TemplatedPolicies = $TemplatedPolicies;
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

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('Policies' === $k) {
                foreach ($v as $vv) {
                    $n->Policies[] = ACLTokenPolicyLink::jsonUnserialize($vv);
                }
            } elseif ('ServiceIdentities' === $k) {
                foreach ($v as $vv) {
                    $n->ServiceIdentities[] = ACLServiceIdentity::jsonUnserialize($vv);
                }
            } elseif ('NodeIdentities' === $k) {
                foreach ($v as $vv) {
                    $n->NodeIdentities[] = ACLNodeIdentity::jsonUnserialize($vv);
                }
            } elseif ('TemplatedPolicies' === $k) {
                foreach ($v as $vv) {
                    $n->TemplatedPolicies[] = ACLTemplatedPolicy::jsonUnserialize($vv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->ID = $this->ID;
        $out->Name = $this->Name;
        $out->Description = $this->Description;
        $out->Hash = $this->Hash;
        if ([] !== $this->Policies) {
            $out->Policies = $this->Policies;
        }
        if ([] !== $this->ServiceIdentities) {
            $out->ServiceIdentities = $this->ServiceIdentities;
        }
        if ([] !== $this->NodeIdentities) {
            $out->NodeIdentities = $this->NodeIdentities;
        }
        if ([] !== $this->TemplatedPolicies) {
            $out->TemplatedPolicies = $this->TemplatedPolicies;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        return $out;
    }
}
