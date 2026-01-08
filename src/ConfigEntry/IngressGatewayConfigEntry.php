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

class IngressGatewayConfigEntry extends AbstractType implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $Name;
    public string $Partition;
    public GatewayTLSConfig $TLS;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\IngressListener> */
    public array $Listeners;
    public null|IngressServiceConfig $Defaults;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\IngressListener> $Listeners
     * @param array<string,string> $Meta
     */
    public function __construct(
        string $Kind = '',
        string $Name = '',
        string $Partition = '',
        string $Namespace = '',
        null|GatewayTLSConfig $TLS = null,
        array $Listeners = [],
        array $Meta = [],
        null|IngressServiceConfig $Defaults = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        $this->Kind = $Kind;
        $this->Name = $Name;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->TLS = $TLS ?? new GatewayTLSConfig();
        $this->setListeners(...$Listeners);
        $this->setMeta($Meta);
        $this->Defaults = $Defaults;
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

    public function getTLS(): GatewayTLSConfig
    {
        return $this->TLS;
    }

    public function setTLS(GatewayTLSConfig $TLS): self
    {
        $this->TLS = $TLS;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\IngressListener>
     */
    public function getListeners(): array
    {
        return $this->Listeners;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\IngressListener ...$Listeners
     */
    public function setListeners(IngressListener ...$Listeners): self
    {
        $this->Listeners = $Listeners;
        return $this;
    }

    public function getDefaults(): null|IngressServiceConfig
    {
        return $this->Defaults;
    }

    public function setDefaults(null|IngressServiceConfig $Defaults): self
    {
        $this->Defaults = $Defaults;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('TLS' === $k) {
                $n->TLS = GatewayTLSConfig::jsonUnserialize($v);
            } elseif ('Listeners' === $k) {
                $n->Listeners = [];
                foreach ($v as $vv) {
                    $n->Listeners[] = IngressListener::jsonUnserialize($vv);
                }
            } elseif ('Defaults' === $k) {
                $n->Defaults = null === $v ? null : IngressServiceConfig::jsonUnserialize($v);
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
        $out->TLS = $this->TLS;
        $out->Listeners = $this->Listeners;
        if (isset($this->Meta)) {
            $out->Meta = $this->Meta;
        }
        if (null !== $this->Defaults) {
            $out->Defaults = $this->Defaults;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
