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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class ACLToken
 */
class ACLToken extends AbstractModel
{
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;
    /** @var string */
    public string $AccessorID = '';
    /** @var string */
    public string $SecretID = '';
    /** @var string */
    public string $Description = '';
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink[] */
    public array $Policies = [];
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink[] */
    public array $Roles = [];
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity[] */
    public array $ServiceIdentities = [];
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity[] */
    public array $NodeIdentities = [];
    /** @var bool */
    public bool $Local = false;
    /** @var string|null */
    public ?string $AuthMethod = null;
    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $ExpirationTTL = null;
    /** @var \DCarbone\Go\Time\Time|null */
    public ?Time\Time $ExpirationTime = null;
    /** @var string */
    public string $Hash = '';
    /** @var string|null */
    public ?string $Namespace = null;

    /**
     * @deprecated
     * @var string|null
     */
    public ?string $Rules = null;

    /**
     * @return int
     */
    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    /**
     * @param int $CreateIndex
     */
    public function setCreateIndex(int $CreateIndex): void
    {
        $this->CreateIndex = $CreateIndex;
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
     */
    public function setModifyIndex(int $ModifyIndex): void
    {
        $this->ModifyIndex = $ModifyIndex;
    }

    /**
     * @return string
     */
    public function getAccessorID(): string
    {
        return $this->AccessorID;
    }

    /**
     * @param string $AccessorID
     */
    public function setAccessorID(string $AccessorID): void
    {
        $this->AccessorID = $AccessorID;
    }

    /**
     * @return string
     */
    public function getSecretID(): string
    {
        return $this->SecretID;
    }

    /**
     * @param string $SecretID
     */
    public function setSecretID(string $SecretID): void
    {
        $this->SecretID = $SecretID;
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
     */
    public function setDescription(string $Description): void
    {
        $this->Description = $Description;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink[]
     */
    public function getPolicies(): array
    {
        return $this->Policies;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink[] $Policies
     */
    public function setPolicies(array $Policies): void
    {
        $this->Policies = $Policies;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink[]
     */
    public function getRoles(): array
    {
        return $this->Roles;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink[] $Roles
     */
    public function setRoles(array $Roles): void
    {
        $this->Roles = $Roles;
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
     */
    public function setServiceIdentities(array $ServiceIdentities): void
    {
        $this->ServiceIdentities = $ServiceIdentities;
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
     */
    public function setNodeIdentities(array $NodeIdentities): void
    {
        $this->NodeIdentities = $NodeIdentities;
    }

    /**
     * @return bool
     */
    public function isLocal(): bool
    {
        return $this->Local;
    }

    /**
     * @param bool $Local
     */
    public function setLocal(bool $Local): void
    {
        $this->Local = $Local;
    }

    /**
     * @return string|null
     */
    public function getAuthMethod(): ?string
    {
        return $this->AuthMethod;
    }

    /**
     * @param string|null $AuthMethod
     */
    public function setAuthMethod(?string $AuthMethod): void
    {
        $this->AuthMethod = $AuthMethod;
    }

    /**
     * @return \DCarbone\Go\Time\Duration|null
     */
    public function getExpirationTTL(): ?Time\Duration
    {
        return $this->ExpirationTTL;
    }

    /**
     * @param \DCarbone\Go\Time\Duration|null $ExpirationTTL
     */
    public function setExpirationTTL(?Time\Duration $ExpirationTTL): void
    {
        $this->ExpirationTTL = $ExpirationTTL;
    }

    /**
     * @return \DCarbone\Go\Time\Time|null
     */
    public function getExpirationTime(): ?Time\Time
    {
        return $this->ExpirationTime;
    }

    /**
     * @param \DCarbone\Go\Time\Time|null $ExpirationTime
     */
    public function setExpirationTime(?Time\Time $ExpirationTime): void
    {
        $this->ExpirationTime = $ExpirationTime;
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
     */
    public function setHash(string $Hash): void
    {
        $this->Hash = $Hash;
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
     */
    public function setNamespace(?string $Namespace): void
    {
        $this->Namespace = $Namespace;
    }

    /**
     * @return string|null
     */
    public function getRules(): ?string
    {
        return $this->Rules;
    }

    /**
     * @param string|null $Rules
     */
    public function setRules(?string $Rules): void
    {
        $this->Rules = $Rules;
    }
}
