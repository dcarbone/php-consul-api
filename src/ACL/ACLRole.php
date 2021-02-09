<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class ACLRole
 */
class ACLRole extends AbstractModel
{
    private const FIELD_POLICIES           = 'Policies';
    private const FIELD_ROLES              = 'Roles';
    private const FIELD_SERVICE_IDENTITIES = 'ServiceIdentities';
    private const FIELD_NODE_IDENTITIES    = 'NodeIdentities';
    private const FIELD_NAMESPACE          = 'Namespace';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Description = '';
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLRolePolicyLink[] */
    public array $Policies = [];
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity[] */
    public array $ServiceIdentities = [];
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity[] */
    public array $NodeIdentities = [];
    /** @var string */
    public string $Hash = '';
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;
    /** @var string|null */
    public ?string $Namespace = null;

    /** @var array[] */
    protected const FIELDS = [
        self::FIELD_POLICIES           => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => ACLTokenPolicyLink::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
        self::FIELD_ROLES              => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => ACLTokenRoleLink::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
        self::FIELD_SERVICE_IDENTITIES => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => ACLServiceIdentity::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
        self::FIELD_NODE_IDENTITIES    => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => ACLNodeIdentity::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
        self::FIELD_NAMESPACE          => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
    ];

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
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
     * @param string $Name
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->Description;
    }

    /**
     * @param string $Description
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
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

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLRolePolicyLink[] $Policies
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
    public function setPolicies(array $Policies): self
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

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity[] $ServiceIdentities
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
    public function setServiceIdentities(array $ServiceIdentities): self
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

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity[] $NodeIdentities
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
    public function setNodeIdentities(array $NodeIdentities): self
    {
        $this->NodeIdentities = $NodeIdentities;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->Hash;
    }

    /**
     * @param string $Hash
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
    public function setHash(string $Hash): self
    {
        $this->Hash = $Hash;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    /**
     * @param int $CreateIndex
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    /**
     * @param int $ModifyIndex
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->Namespace;
    }

    /**
     * @param string|null $Namespace
     * @return \DCarbone\PHPConsulAPI\ACL\ACLRole
     */
    public function setNamespace(?string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }
}
