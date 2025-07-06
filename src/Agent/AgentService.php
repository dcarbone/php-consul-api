<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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
use DCarbone\PHPConsulAPI\Catalog\ServiceAddress;
use DCarbone\PHPConsulAPI\MetaContainer;
use DCarbone\PHPConsulAPI\Peering\Locality;

class AgentService extends AbstractModel
{
    use MetaContainer;

    public ServiceKind $Kind;
    public string $ID;
    public string $Service;
    /** @var array<string> */
    public array $Tags;
    public int $Port;
    public string $Address;
    public string $SocketPath;
    public null|\stdClass $TaggedAddresses;
    public AgentWeights $Weights;
    public bool $EnableTagOverride;
    public int $CreateIndex;
    public int $ModifyIndex;
    public string $ContentHash;
    public null|AgentServiceConnectProxyConfig $Proxy;
    public null|AgentServiceConnect $Connect;
    public string $PeerName;
    public string $Namespace;
    public string $Partition;
    public string $Datacenter;
    public null|Locality $Locality;

    /**
     * @param array<string> $Tags
     */
    public function __construct(
        string|ServiceKind $Kind = '',
        string $ID = '',
        string $Service = '',
        string $SocketPath = '',
        array $Tags = [],
        null|\stdClass $Meta = null,
        int $Port = 0,
        string $Address = '',
        null|\stdClass $TaggedAddresses = null,
        null|AgentWeights $Weights = null,
        bool $EnableTagOverride = false,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        string $ContentHash = '',
        null|AgentServiceConnectProxyConfig $Proxy = null,
        null|AgentServiceConnect $Connect = null,
        string $PeerName = '',
        string $Namespace = '',
        string $Partition = '',
        string $Datacenter = '',
        null|Locality $Locality = null,
    ) {
        $this->Kind = is_string($Kind) ? ServiceKind::from($Kind) : $Kind;
        $this->ID = $ID;
        $this->Service = $Service;
        $this->Meta = $Meta;
        $this->Port = $Port;
        $this->setTags(...$Tags);
        $this->Address = $Address;
        $this->SocketPath = $SocketPath;
        $this->setTaggedAddresses($TaggedAddresses);
        $this->Weights = $Weights ?? new AgentWeights();
        $this->EnableTagOverride = $EnableTagOverride;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->ContentHash = $ContentHash;
        $this->Proxy = $Proxy;
        $this->Connect = $Connect;
        $this->PeerName = $PeerName;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        $this->Datacenter = $Datacenter;
        $this->Locality = $Locality;
}

    public function getKind(): ServiceKind
    {
        return $this->Kind;
    }

    public function setKind(string|ServiceKind $Kind): self
    {
        $this->Kind = is_string($Kind) ? ServiceKind::from($Kind) : $Kind;
        return $this;
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getService(): string
    {
        return $this->Service;
    }

    public function setService(string $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->Tags;
    }

    public function setTags(string ...$Tags): self
    {
        $this->Tags = $Tags;
        return $this;
    }

    public function getPort(): int
    {
        return $this->Port;
    }

    public function setPort(int $Port): self
    {
        $this->Port = $Port;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    public function getTaggedAddresses(): null|\stdClass
    {
        return $this->TaggedAddresses;
    }

    public function setTaggedAddresses(null|\stdClass $TaggedAddresses): self
    {
        if (null === $TaggedAddresses) {
            $this->TaggedAddresses = null;
            return $this;
        }
        $this->TaggedAddresses = new \stdClass();
        foreacH($TaggedAddresses as $k => $v) {
            $this->TaggedAddresses->{$k} = $v instanceof ServiceAddress ? $v : ServiceAddress::jsonUnserialize($v);
        }
        return $this;
    }

    public function getWeights(): AgentWeights
    {
        return $this->Weights;
    }

    public function setWeights(AgentWeights $Weights): self
    {
        $this->Weights = $Weights;
        return $this;
    }

    public function isEnableTagOverride(): bool
    {
        return $this->EnableTagOverride;
    }

    public function setEnableTagOverride(bool $EnableTagOverride): self
    {
        $this->EnableTagOverride = $EnableTagOverride;
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

    public function getContentHash(): string
    {
        return $this->ContentHash;
    }

    public function setContentHash(string $ContentHash): self
    {
        $this->ContentHash = $ContentHash;
        return $this;
    }

    public function getProxy(): null|AgentServiceConnectProxyConfig
    {
        return $this->Proxy;
    }

    public function setProxy(null|AgentServiceConnectProxyConfig $Proxy): self
    {
        $this->Proxy = $Proxy;
        return $this;
    }

    public function getConnect(): null|AgentServiceConnect
    {
        return $this->Connect;
    }

    public function setConnect(null|AgentServiceConnect $Connect): self
    {
        $this->Connect = $Connect;
        return $this;
    }

    public function getPeerName(): string
    {
        return $this->PeerName;
    }

    public function setPeerName(string $PeerName): self
    {
        $this->PeerName = $PeerName;
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

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    public function getLocality(): null|Locality
    {
        return $this->Locality;
    }

    public function setLocality(null|Locality $Locality): self
    {
        $this->Locality = $Locality;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Kind' === $k) {
                $n->Kind = ServiceKind::from($v);
            } elseif ('Tags' === $k) {
                $n->setTags(...$v);
            } elseif ('Proxy' === $k) {
                $n->Proxy = null === $v ? null : AgentServiceConnectProxyConfig::jsonUnserialize($v);
            } elseif ('Weights' === $k) {
                $n->Weights = AgentWeights::jsonUnserialize($v);
            } elseif ('TaggedAddresses' === $k) {
                $n->settaggedAddresses($v);
            } elseif ('Connect' === $k) {
                $n->Connect = null === $v ? null : AgentServiceConnect::jsonUnserialize($v);
            } elseif ('Locality' === $k) {
                $n->Locality = null === $v ? null : Locality::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (ServiceKind::Typical !== $this->Kind) {
            $out->Kind = $this->Kind;
        }
        $out->ID = $this->ID;
        $out->Service = $this->Service;
        $out->Tags = $this->Tags;
        $out->Meta = $this->Meta;
        $out->Port = $this->Port;
        $out->Address = $this->Address;
        if ('' !== $this->SocketPath) {
            $out->SocketPath = $this->SocketPath;
        }
        if (null !== $this->TaggedAddresses) {
            $out->TaggedAddresses = $this->TaggedAddresses;
        }
        $out->Weights = $this->Weights;
        $out->EnableTagOverride = $this->EnableTagOverride;
        if (0 !== $this->CreateIndex) {
            $out->CreateIndex = $this->CreateIndex;
        }
        if (0 !== $this->ModifyIndex) {
            $out->ModifyIndex = $this->ModifyIndex;
        }
        if ('' !== $this->ContentHash) {
            $out->ContentHash = $this->ContentHash;
        }
        if (null !== $this->Proxy) {
            $out->Proxy = $this->Proxy;
        }
        if (null !== $this->Connect) {
            $out->Connect = $this->Connect;
        }
        if ('' !== $this->PeerName) {
            $out->PeerName = $this->PeerName;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Datacenter) {
            $out->Datacenter = $this->Datacenter;
        }
        if (null !== $this->Locality) {
            $out->Locality = $this->Locality;
        }
        return $out;
    }
}
