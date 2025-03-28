<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

use DCarbone\PHPConsulAPI\FakeMap;

trait ConfigEntryTrait
{
    public string $Kind = '';
    public string $Name = '';
    public string $Namespace = '';
    public ?FakeMap $Meta = null;
    public int $CreateIndex = 0;
    public int $ModifyIndex = 0;

    public function getKind(): string
    {
        return $this->Kind;
    }

    public function setKind(string $Kind): ConfigEntry
    {
        $this->Kind = $Kind;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): ConfigEntry
    {
        $this->Name = $Name;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): ConfigEntry
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function getMeta(): ?FakeMap
    {
        return $this->Meta;
    }

    public function setMeta(mixed $Meta): ProxyConfigEntry
    {
        $this->Meta = FakeMap::parse($Meta);
        return $this;
    }

    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int $CreateIndex): ProxyConfigEntry
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int $ModifyIndex): ProxyConfigEntry
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }
}
