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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class ServiceSplitterConfigEntry extends AbstractType implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $Name;
    public string $Partition;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplit> */
    public array $Splits;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplit> $Splits
     * @param array<string,string> $Meta
     */
    public function __construct(
        string $Kind = '',
        string $Name = '',
        string $Partition = '',
        string $Namespace = '',
        array $Splits = [],
        null|\stdClass|array $Meta = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        $this->Kind = $Kind;
        $this->Name = $Name;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->setSplits(...$Splits);
        $this->setMeta($Meta);
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
    }

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
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplit>
     */
    public function getSplits(): array
    {
        return $this->Splits;
    }

    public function setSplits(ServiceSplit ...$Splits): self
    {
        $this->Splits = $Splits;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Splits' === $k) {
                $n->Splits = [];
                foreach ($v as $vv) {
                    $n->Splits[] = ServiceSplit::jsonUnserialize($vv);
                }
            } elseif ('Meta' === $k) {
                $n->setMeta($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Kind = $this->Kind;
        $out->Name = $this->Name;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ([] !== $this->Splits) {
            $out->Splits = $this->Splits;
        }
        if (isset($this->Meta)) {
            $out->Meta = $this->Meta;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
