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
 * Class ACLAuthMethod
 */
class ACLAuthMethod extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_DISPLAY_NAME    => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_DESCRIPTION     => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_MAX_TOKEN_TTL   => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_DURATION,
            Transcoding::FIELD_MARSHAL_AS         => Transcoding::STRING,
            Transcoding::FIELD_OMITEMPTY          => true,
        ],
        self::FIELD_TOKEN_LOCALITY  => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAMESPACE_RULES => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ACLAuthMethodNamespaceRule::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::class,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_NAMESPACE       => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_DISPLAY_NAME    = 'DisplayName';
    private const FIELD_DESCRIPTION     = 'Description';
    private const FIELD_MAX_TOKEN_TTL   = 'MaxTokenTTL';
    private const FIELD_TOKEN_LOCALITY  = 'TokenLocality';
    private const FIELD_NAMESPACE_RULES = 'NamespaceRules';
    private const FIELD_NAMESPACE       = 'Namespace';

    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Type = '';
    /** @var string */
    public string $DisplayName = '';
    /** @var string */
    public string $Description = '';
    /** @var \DCarbone\Go\Time\Duration */
    public Time\Duration $MaxTokenTTL;
    /** @var string */
    public string $TokenLocality = '';
    /** @var array */
    public array $config = [];
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule[] */
    public array $NamespaceRules = [];
    /** @var string */
    public string $Namespace = '';

    /**
     * ACLAuthMethod constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!isset($this->MaxTokenTTL)) {
            $this->MaxTokenTTL = new Time\Duration();
        }
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->Type;
    }

    /**
     * @param string $Type
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setType(string $Type): self
    {
        $this->Type = $Type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->DisplayName;
    }

    /**
     * @param string $DisplayName
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setDisplayName(string $DisplayName): self
    {
        $this->DisplayName = $DisplayName;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setDescription(string $Description): self
    {
        $this->Description = $Description;
        return $this;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getMaxTokenTTL(): Time\Duration
    {
        return $this->MaxTokenTTL;
    }

    /**
     * @param \DCarbone\Go\Time\Duration|int|string $MaxTokenTTL
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setMaxTokenTTL($MaxTokenTTL): self
    {
        $this->MaxTokenTTL = Time::ParseDuration($MaxTokenTTL);
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenLocality(): string
    {
        return $this->TokenLocality;
    }

    /**
     * @param string $TokenLocality
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setTokenLocality(string $TokenLocality): self
    {
        $this->TokenLocality = $TokenLocality;
        return $this;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule[]
     */
    public function getNamespaceRules(): array
    {
        return $this->NamespaceRules;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule[] $NamespaceRules
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setNamespaceRules(array $NamespaceRules): self
    {
        $this->NamespaceRules = $NamespaceRules;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethod
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }
}
