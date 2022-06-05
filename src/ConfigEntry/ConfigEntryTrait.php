<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

use DCarbone\PHPConsulAPI\FakeMap;

/**
 * Trait ConfigEntryTrait
 */
trait ConfigEntryTrait
{
    /** @var string */
    public string $Kind = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Partition = '';
    /** @var string  */
    public string $Namespace = '';
    /** @var \DCarbone\PHPConsulAPI\FakeMap|null */
    public ?FakeMap $Meta = null;
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->Kind;
    }

    /**
     * @param string $Kind
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ConfigEntry
     */
    public function setKind(string $Kind): ConfigEntry
    {
        $this->Kind = $Kind;
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
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ConfigEntry
     */
    public function setName(string $Name): ConfigEntry
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPartition(): string
    {
        return $this->Partition;
    }

    /**
     * @param string $Partition
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ConfigEntry
     */
    public function setPartition(string $Partition): ConfigEntry
    {
        $this->Partition = $Partition;
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
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ConfigEntry
     */
    public function setNamespace(string $Namespace): ConfigEntry
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\FakeMap|null
     */
    public function getMeta(): ?FakeMap
    {
        return $this->Meta;
    }

    /**
     * @param mixed $Meta
     * @return ProxyConfigEntry
     */
    public function setMeta($Meta): ProxyConfigEntry
    {
        $this->Meta = FakeMap::parse($Meta);
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
     * @return ProxyConfigEntry
     */
    public function setCreateIndex(int $CreateIndex): ProxyConfigEntry
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
     * @return ProxyConfigEntry
     */
    public function setModifyIndex(int $ModifyIndex): ProxyConfigEntry
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }
}
