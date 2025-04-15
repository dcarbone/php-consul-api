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

class ACLAuthMethodListEntry extends AbstractModel
{
    public string $Name;
    public string $Type;
    public string $DisplayName;
    public string $Description;
    public Time\Duration $MaxTokenTTL;
    /**
     * TokenLocality defines the kind of token that this auth method produces.
     * This can be either 'local' or 'global'. If empty 'local' is assumed.
     * @var string
     */
    public string $TokenLocality;
    public int $CreateIndex;
    public int $ModifyIndex;
    public string $Namespace;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $Name = '',
        string $Type = '',
        string $DisplayName = '',
        string $Description = '',
        null|int|float|string|\DateInterval|Time\Duration $MaxTokenTTL = null,
        string $TokenLocality = '',
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        string $Namespace = ''
    ) {
        $this->Name = $Name;
        $this->Type = $Type;
        $this->DisplayName = $DisplayName;
        $this->Description = $Description;
        $this->setMaxTokenTTL($MaxTokenTTL);
        $this->TokenLocality = $TokenLocality;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->Namespace = $Namespace;
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

    /**
     * TokenLocality defines the kind of token that this auth method produces.
     * This can be either 'local' or 'global'. If empty 'local' is assumed.
     *
     * @return string
     */
    public function getTokenLocality(): string
    {
        return $this->TokenLocality;
    }

    /**
     * TokenLocality defines the kind of token that this auth method produces.
     * This can be either 'local' or 'global'. If empty 'local' is assumed.
     *
     * @param string $TokenLocality
     * @return \DCarbone\PHPConsulAPI\ACL\ACLAuthMethodListEntry
     */
    public function setTokenLocality(string $TokenLocality): self
    {
        $this->TokenLocality = $TokenLocality;
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if (null === $v) {
                continue;
            }
            if ('MaxTokenTTL' === $k) {
                $n->setMaxTokenTTL($v);
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
        $out->Type = $this->Type;
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
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        return $out;
    }
}
