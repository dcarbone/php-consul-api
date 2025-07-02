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

use DCarbone\PHPConsulAPI\AbstractModel;

use function DCarbone\PHPConsulAPI\_enc_obj_if_valued;

class TerminatingGatewayConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $Name;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\LinkedService> */
    public array $Services;
    public string $Partition;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\LinkedService> $Services
     */
    public function __construct(
        string $Kind = '',
        string $Name = '',
        array $Services = [],
        null|\stdClass $Meta = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        string $Partition = '',
        string $Namespace = '',
    ) {
        $this->Kind = $Kind;
        $this->Name = $Name;
        $this->setServices(...$Services);
        $this->Meta = $Meta;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
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

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\LinkedService>
     */
    public function getServices(): array
    {
        return $this->Services;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\LinkedService ...$Services
     */
    public function setServices(LinkedService ...$Services): self
    {
        $this->Services = $Services;
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

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Services' === $k) {
                $n->Services = [];
                foreach ($v as $vv) {
                    $n->Services[] = LinkedService::jsonUnserialize($vv);
                }
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
        if ([] !== $this->Services) {
            $out->Services = $this->Services;
        }
        if (null !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        return $out;
    }
}
