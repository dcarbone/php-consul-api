<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class GenericConfigEntry extends AbstractType implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $Name;
    public string $Partition;

    /**
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $Kind = '',
        string $Name = '',
        string $Partition = '',
        string $Namespace = '',
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Kind = $Kind;
        $this->Name = $Name;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
    }

    /** @var array<string, mixed> */
    public array $Data = [];

    public function getKind(): string
    {
        return $this->Kind;
    }

    public function setKind(string $Kind): self
    {
        $this->Kind = $Kind;
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

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->Data;
    }

    /**
     * @param array<string, mixed> $Data
     */
    public function setData(array $Data): self
    {
        $this->Data = $Data;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('Meta' === $k) {
                $n->setMeta($v);
            } elseif ('Kind' === $k || 'kind' === $k) {
                $n->Kind = $v;
            } elseif ('Name' === $k || 'name' === $k) {
                $n->Name = $v;
            } elseif ('Partition' === $k || 'partition' === $k) {
                $n->Partition = $v;
            } elseif ('Namespace' === $k || 'namespace' === $k) {
                $n->Namespace = $v;
            } elseif ('CreateIndex' === $k || 'create_index' === $k) {
                $n->CreateIndex = $v;
            } elseif ('ModifyIndex' === $k || 'modify_index' === $k) {
                $n->ModifyIndex = $v;
            } else {
                $n->Data[$k] = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        foreach ($this->Data as $k => $v) {
            $out->{$k} = $v;
        }
        $out->Kind = $this->Kind;
        $out->Name = $this->Name;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if (null !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
