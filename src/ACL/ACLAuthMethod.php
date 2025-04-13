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

class ACLAuthMethod extends AbstractModel
{
    public string $ID;
    public string $Name;
    public string $Type;
    public string $DisplayName;
    public string $Description;
    public Time\Duration $MaxTokenTTL;
    public string $TokenLocality;
    public array $config;
    public int $CreateIndex;
    public int $ModifyIndex;
    public array $NamespaceRules;
    public string $Namespace;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $ID = '',
        string $Name = '',
        string $Type = '',
        string $DisplayName = '',
        string $Description = '',
        null|int|float|string|\DateInterval|Time\Duration $MaxTokenTTL = null,
        string $TokenLocality = '',
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        iterable $NamespaceRules = [],
        string $Namespace = ''
    ) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Type = $Type;
        $this->DisplayName = $DisplayName;
        $this->Description = $Description;
        $this->setMaxTokenTTL($MaxTokenTTL);
        $this->TokenLocality = $TokenLocality;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->setNamespaceRules(...$NamespaceRules);
        $this->Namespace = $Namespace;
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
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

    public function setMaxTokenTTL(null|int|float|string|\DateInterval|Time\Duration $MaxTokenTTL): self
    {
        $this->MaxTokenTTL = Time::Duration($MaxTokenTTL);
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

    public function addNamespaceRule(ACLAuthMethodNamespaceRule $rule): self
    {
        $this->NamespaceRules[] = $rule;
        return $this;
    }

    public function setNamespaceRules(ACLAuthMethodNamespaceRule ...$rules): self
    {
        $this->NamespaceRules = $rules;
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new static();
        foreach ($decoded as $k => $v) {
            switch ($k) {
                case 'MaxTokenTTL':
                    $n->setMaxTokenTTL($v);
                    break;
                case 'NamespaceRules':
                    foreach ($v as $vv) {
                        $n->addNamespaceRule(ACLAuthMethodNamespaceRule::jsonUnserialize($vv));
                    }
                    break;

                default:
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
        if ('' !== $this->DisplayName) {
            $out->DisplayName = $this->DisplayName;
        }
        if ('' !== $this->Description) {
            $out->Description = $this->Description;
        }
        if (0 !== $this->MaxTokenTTL->Nanoseconds()) {
            $out->MaxTokenTTL = (string)$this->MaxTokenTTL;
        }
        if ('' !== $this->TokenLocality) {
            $out->TokenLocality = $this->TokenLocality;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        if ([] !== $this->NamespaceRules) {
            $out->NamespaceRules = $this->NamespaceRules;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        return $out;
    }
}
