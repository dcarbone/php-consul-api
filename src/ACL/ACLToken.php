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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ACLToken
 */
class ACLToken extends AbstractModel
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
        self::FIELD_EXPIRATION_TTL     => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_DURATION,
            Transcoding::FIELD_OMITEMPTY          => true,
        ],
        self::FIELD_EXPIRATION_TIME    => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_NULLABLE_TIME,
            Transcoding::FIELD_NULLABLE           => true,
            Transcoding::FIELD_OMITEMPTY          => true,
        ],
        self::FIELD_CREATE_TIME        => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_TIME,
            Transcoding::FIELD_OMITEMPTY          => true,
        ],
        self::FIELD_RULES              => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAMESPACE          => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_POLICIES           = 'Policies';
    private const FIELD_ROLES              = 'Roles';
    private const FIELD_SERVICE_IDENTITIES = 'ServiceIdentities';
    private const FIELD_NODE_IDENTITIES    = 'NodeIdentities';
    private const FIELD_AUTH_METHOD        = 'AuthMethod';
    private const FIELD_EXPIRATION_TTL     = 'ExpirationTTL';
    private const FIELD_EXPIRATION_TIME    = 'ExpirationTime';
    private const FIELD_CREATE_TIME        = 'CreateTime';
    private const FIELD_RULES              = 'Rules';
    private const FIELD_NAMESPACE          = 'Namespace';

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
    /** @var string */
    public string $AuthMethod = '';
    /** @var \DCarbone\Go\Time\Duration */
    public Time\Duration $ExpirationTTL;
    /** @var \DCarbone\Go\Time\Time|null */
    public ?Time\Time $ExpirationTime = null;
    /** @var \DCarbone\Go\Time\Time */
    public Time\Time $CreateTime;
    /** @var string */
    public string $Hash = '';
    /** @var string */
    public string $Namespace = '';

    /**
     * @deprecated
     * @var string
     */
    public string $Rules = '';

    /**
     * ACLToken constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!isset($this->ExpirationTTL)) {
            $this->ExpirationTTL = new Time\Duration();
        }
        if (!isset($this->CreateTime)) {
            $this->CreateTime = Time::New();
        }
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setAccessorID(string $AccessorID): self
    {
        $this->AccessorID = $AccessorID;
        return $this;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setSecretID(string $SecretID): self
    {
        $this->SecretID = $SecretID;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
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

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink[] $Policies
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setPolicies(array $Policies): self
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

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink[] $Roles
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setRoles(array $Roles): self
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

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity[] $ServiceIdentities
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setNodeIdentities(array $NodeIdentities): self
    {
        $this->NodeIdentities = $NodeIdentities;
        return $this;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setLocal(bool $Local): self
    {
        $this->Local = $Local;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthMethod(): string
    {
        return $this->AuthMethod;
    }

    /**
     * @param string $AuthMethod
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setAuthMethod(string $AuthMethod): self
    {
        $this->AuthMethod = $AuthMethod;
        return $this;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getExpirationTTL(): Time\Duration
    {
        return $this->ExpirationTTL;
    }

    /**
     * @param \DCarbone\Go\Time\Duration $ExpirationTTL
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setExpirationTTL(Time\Duration $ExpirationTTL): self
    {
        $this->ExpirationTTL = $ExpirationTTL;
        return $this;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setExpirationTime(?Time\Time $ExpirationTime): self
    {
        $this->ExpirationTime = $ExpirationTime;
        return $this;
    }

    /**
     * @return \DCarbone\Go\Time\Time
     */
    public function getCreateTime(): Time\Time
    {
        return $this->CreateTime;
    }

    /**
     * @param \DCarbone\Go\Time\Time $CreateTime
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setCreateTime(Time\Time $CreateTime): self
    {
        $this->CreateTime = $CreateTime;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setHash(string $Hash): self
    {
        $this->Hash = $Hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $Namespace
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getRules(): string
    {
        return $this->Rules;
    }

    /**
     * @param string $Rules
     * @return \DCarbone\PHPConsulAPI\ACL\ACLToken
     */
    public function setRules(string $Rules): self
    {
        $this->Rules = $Rules;
        return $this;
    }
}
