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
    public string $Name;
    public string $Type;
    public string $DisplayName;
    public string $Description;
    public Time\Duration $MaxTokenTTL;
    public string $TokenLocality;
    public null|\stdClass $Config;
    public int $CreateIndex;
    public int $ModifyIndex;
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule[] */
    public array $NamespaceRules;
    public string $Namespace;
    public string $Partition;

    /**
     * @param array<string, mixed>|null $data Deprecated, will be removed.
     * @param string $Name
     * @param string $Type
     * @param string $DisplayName
     * @param string $Description
     * @param int|float|string|\DateInterval|\DCarbone\Go\Time\Duration|null $MaxTokenTTL
     * @param string $TokenLocality
     * @param null|\stdClass $Config
     * @param int $CreateIndex
     * @param int $ModifyIndex
     * @param iterable<\DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule> $NamespaceRules
     * @param string $Namespace
     * @param string $Partition
     */
    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $Name = '',
        string $Type = '',
        string $DisplayName = '',
        string  $Description = '',
        null|int|float|string|\DateInterval|Time\Duration $MaxTokenTTL = null,
        string $TokenLocality = '',
        null|\stdClass $Config = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        iterable $NamespaceRules = [],
        string $Namespace = '',
        string $Partition = '',
    ) {
        $this->Name = $Name;
        $this->Type = $Type;
        $this->DisplayName = $DisplayName;
        $this->Description = $Description;
        $this->setMaxTokenTTL($MaxTokenTTL);
        $this->TokenLocality = $TokenLocality;
        $this->Config = $Config;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->setNamespaceRules(...$NamespaceRules);
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
        }
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

    public function getConfig(): null|\stdClass
    {
        return $this->Config;
    }

    public function setConfig(null|\stdClass $Config): self
    {
        $this->Config = $Config;
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

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodNamespaceRule[]
     */
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
            if ('MaxTokenTTL' === $k) {
                $n->setMaxTokenTTL($v);
            } elseif ('NamespaceRules' === $k) {
                $n->NamespaceRules = [];
                foreach ($v as $vv) {
                    $n->NamespaceRules[] = ACLAuthMethodNamespaceRule::jsonUnserialize($vv);
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
        $out->Config = $this->Config;
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        if ([] !== $this->NamespaceRules) {
            $out->NamespaceRules = $this->NamespaceRules;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        return $out;
    }
}
