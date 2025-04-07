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

    public string $ID = '';
    public string $Name = '';
    public string $Type = '';
    public string $DisplayName = '';
    public string $Description = '';
    public Time\Duration $MaxTokenTTL;
    public string $TokenLocality = '';
    public array $config = [];
    public int $CreateIndex = 0;
    public int $ModifyIndex = 0;
    public array $NamespaceRules = [];
    public string $Namespace = '';

    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!isset($this->MaxTokenTTL)) {
            $this->MaxTokenTTL = new Time\Duration();
        }
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): ACLAuthMethod
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

    public function getType(): string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;
        return $this;
    }

    public function getDisplayName(): string
    {
        return $this->DisplayName;
    }

    public function setDisplayName(string $DisplayName): self
    {
        $this->DisplayName = $DisplayName;
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

    public function getMaxTokenTTL(): Time\Duration
    {
        return $this->MaxTokenTTL;
    }

    public function setMaxTokenTTL(int|string|Time\Duration $MaxTokenTTL): self
    {
        $this->MaxTokenTTL = Time::ParseDuration($MaxTokenTTL);
        return $this;
    }

    public function getTokenLocality(): string
    {
        return $this->TokenLocality;
    }

    public function setTokenLocality(string $TokenLocality): self
    {
        $this->TokenLocality = $TokenLocality;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): self
    {
        $this->config = $config;
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

    public function getNamespaceRules(): array
    {
        return $this->NamespaceRules;
    }

    public function setNamespaceRules(array $NamespaceRules): self
    {
        $this->NamespaceRules = $NamespaceRules;
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
