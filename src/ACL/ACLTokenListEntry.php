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
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

class ACLTokenListEntry extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_POLICIES           => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ACLTokenPolicyLink::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_ROLES              => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ACLTokenRoleLink::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_SERVICE_IDENTITIES => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ACLServiceIdentity::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_NODE_IDENTITIES    => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ACLNodeIdentity::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_AUTH_METHOD        => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_EXPIRATION_TIME    => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_NULLABLE_TIME,
            Transcoding::FIELD_NULLABLE           => true,
            Transcoding::FIELD_OMITEMPTY          => true,
        ],
        self::FIELD_CREATE_TIME        => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_TIME,
        ],
        self::FIELD_NAMESPACE          => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_POLICIES           = 'Policies';
    private const FIELD_ROLES              = 'Roles';
    private const FIELD_SERVICE_IDENTITIES = 'ServiceIdentities';
    private const FIELD_NODE_IDENTITIES    = 'NodeIdentities';
    private const FIELD_AUTH_METHOD        = 'AuthMethod';
    private const FIELD_EXPIRATION_TIME    = 'ExpirationTime';
    private const FIELD_CREATE_TIME        = 'CreateTime';
    private const FIELD_NAMESPACE          = 'Namespace';

    public int $CreateIndex = 0;
    public int $ModifyIndex = 0;
    public string $AccessorID = '';
    public string $Description = '';
    public array $Policies = [];
    public array $Roles = [];
    public array $ServiceIdentities = [];
    public array $NodeIdentities = [];
    public bool $Local = false;
    public string $AuthMethod = '';
    public ?Time\Time $ExpirationTime = null;
    public Time\Time $CreateTime;
    public string $Hash = '';
    public bool $Legacy = false;
    public string $Namespace = '';

    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!isset($this->CreateTime)) {
            $this->CreateTime = Time::New();
        }
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

    public function getAccessorID(): string
    {
        return $this->AccessorID;
    }

    public function setAccessorID(string $AccessorID): self
    {
        $this->AccessorID = $AccessorID;
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

    public function getPolicies(): array
    {
        return $this->Policies;
    }

    public function setPolicies(array $Policies): self
    {
        $this->Policies = $Policies;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->Roles;
    }

    public function setRoles(array $Roles): self
    {
        $this->Roles = $Roles;
        return $this;
    }

    public function getServiceIdentities(): array
    {
        return $this->ServiceIdentities;
    }

    public function setServiceIdentities(array $ServiceIdentities): self
    {
        $this->ServiceIdentities = $ServiceIdentities;
        return $this;
    }

    public function getNodeIdentities(): array
    {
        return $this->NodeIdentities;
    }

    public function setNodeIdentities(array $NodeIdentities): self
    {
        $this->NodeIdentities = $NodeIdentities;
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

    public function getExpirationTime(): ?Time\Time
    {
        return $this->ExpirationTime;
    }

    public function setExpirationTime(?Time\Time $ExpirationTime): self
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

    public function isLegacy(): bool
    {
        return $this->Legacy;
    }

    public function setLegacy(bool $Legacy): self
    {
        $this->Legacy = $Legacy;
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
}
