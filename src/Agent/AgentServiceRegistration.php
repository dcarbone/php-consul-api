<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\Catalog\ServiceAddress;
use DCarbone\PHPConsulAPI\Peering\Locality;
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;
use DCarbone\PHPConsulAPI\PHPLib\MetaField;

class AgentServiceRegistration extends AbstractType
{
    use MetaField;

    public ServiceKind $Kind;
    public string $ID;
    public string $Name;
    /** @var string[] */
    public array $Tags;
    public int $Port;
    /** @var array<ServicePort> */
    public array $Ports;
    public string $Address;
    public string $SocketPath;
    /** @var null|array<\DCarbone\PHPConsulAPI\Catalog\ServiceAddress> */
    public null|array $TaggedAddresses = null;
    public bool $EnableTagOverride;
    public null|AgentWeights $Weights;
    public null|AgentServiceCheck $Check;
    public AgentServiceChecks $Checks;
    public null|AgentServiceConnectProxyConfig $Proxy;
    public null|AgentServiceConnect $Connect;
    public string $Namespace;
    public string $Partition;
    public null|Locality $Locality;

    /**
     * @param array<string> $Tags
     * @param array<ServicePort> $Ports
     * @param array<string,\DCarbone\PHPConsulAPI\Catalog\ServiceAddress> $TaggedAddresses
     * @param array<string,string> $Meta
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string|ServiceKind $Kind = ServiceKind::Typical,
        string $ID = '',
        string $Name = '',
        array $Tags = [],
        int $Port = 0,
        array $Ports = [],
        string $Address = '',
        string $SocketPath = '',
        array $TaggedAddresses = [],
        bool $EnableTagOverride = false,
        array $Meta = [],
        null|AgentWeights $Weights = null,
        null|AgentServiceCheck $Check = null,
        null|AgentServiceChecks $Checks = null,
        null|AgentServiceConnectProxyConfig $Proxy = null,
        null|AgentServiceConnect $Connect = null,
        string $Namespace = '',
        string $Partition = '',
        null|Locality $Locality = null,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Kind = is_string($Kind) ? ServiceKind::from($Kind) : $Kind;
        $this->ID = $ID;
        $this->Name = $Name;
        $this->setTags(...$Tags);
        $this->Port = $Port;
        $this->setPorts(...$Ports);
        $this->Address = $Address;
        $this->SocketPath = $SocketPath;
        $this->setTaggedAddresses($TaggedAddresses ?: null);
        $this->EnableTagOverride = $EnableTagOverride;
        $this->setMeta($Meta);
        $this->Weights = $Weights;
        $this->Check = $Check;
        $this->Checks = $Checks ?? new AgentServiceChecks();
        $this->Proxy = $Proxy;
        $this->Connect = $Connect;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        $this->Locality = $Locality;
    }

    public function getKind(): ServiceKind
    {
        return $this->Kind;
    }

    public function setKind(string|ServiceKind $Kind): self
    {
        $this->Kind = $Kind instanceof ServiceKind ? $Kind : ServiceKind::from($Kind);
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
     * @return string[]
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

    /**
     * @return array<ServicePort>
     */
    public function getPorts(): array
    {
        return $this->Ports;
    }

    public function setPorts(ServicePort ...$Ports): self
    {
        $this->Ports = $Ports;
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

    public function getSocketPath(): string
    {
        return $this->SocketPath;
    }

    public function setSocketPath(string $SocketPath): self
    {
        $this->SocketPath = $SocketPath;
        return $this;
    }

    /**
     * @return array<string,\DCarbone\PHPConsulAPI\Catalog\ServiceAddress>|null
     */
    public function getTaggedAddresses(): null|array
    {
        return $this->TaggedAddresses;
    }

    public function setTaggedAddress(string $Tag, ServiceAddress $Address): self
    {
        if (null === $this->TaggedAddresses) {
            $this->TaggedAddresses = [];
        }
        $this->TaggedAddresses[$Tag] = $Address;
        return $this;
    }

    /**
     * @param array<string,\DCarbone\PHPConsulAPI\Catalog\ServiceAddress>|null $TaggedAddresses
     * @return $this
     */
    public function setTaggedAddresses(null|array $TaggedAddresses): self
    {
        if (null === $TaggedAddresses) {
            $this->TaggedAddresses = null;
            return $this;
        }
        $this->TaggedAddresses = [];
        foreach ($TaggedAddresses as $k => $v) {
            $this->setTaggedAddress($k, $v);
        }
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

    public function getWeights(): null|AgentWeights
    {
        return $this->Weights;
    }

    public function setWeights(null|AgentWeights $Weights): self
    {
        $this->Weights = $Weights;
        return $this;
    }

    public function getCheck(): null|AgentServiceCheck
    {
        return $this->Check;
    }

    public function setCheck(null|AgentServiceCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }

    public function getChecks(): AgentServiceChecks
    {
        return $this->Checks;
    }

    public function setChecks(AgentServiceChecks $Checks): self
    {
        $this->Checks = $Checks;
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
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('Kind' === $k) {
                $n->setKind($v);
            } elseif ('Tags' === $k) {
                $n->setTags(...$v);
            } elseif ('Ports' === $k) {
                $n->Ports = [];
                if (null !== $v) {
                    foreach ($v as $vv) {
                        $n->Ports[] = ServicePort::jsonUnserialize($vv);
                    }
                }
            } elseif ('TaggedAddresses' === $k) {
                $n->TaggedAddresses = [];
                foreach ($v as $kk => $vv) {
                    $n->TaggedAddresses[$kk] = ServiceAddress::jsonUnserialize($vv);
                }
            } elseif ('Weights' === $k) {
                $n->Weights = AgentWeights::jsonUnserialize($v);
            } elseif ('Check' === $k) {
                $n->Check = AgentServiceCheck::jsonUnserialize($v);
            } elseif ('Checks' === $k) {
                $n->Checks = AgentServiceChecks::jsonUnserialize($v);
            } elseif ('Proxy' === $k) {
                $n->Proxy = AgentServiceConnectProxyConfig::jsonUnserialize($v);
            } elseif ('Connect' === $k) {
                $n->Connect = AgentServiceConnect::jsonUnserialize($v);
            } elseif ('Locality' === $k) {
                $n->Locality = Locality::jsonUnserialize($v);
            } elseif ('Meta' === $k) {
                $n->setMeta($v);
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ($this->Kind !== ServiceKind::Typical) {
            $out->Kind = $this->Kind;
        }
        if ('' !== $this->ID) {
            $out->ID = $this->ID;
        }
        if ('' !== $this->Name) {
            $out->Name = $this->Name;
        }
        if ([] !== $this->Tags) {
            $out->Tags = $this->Tags;
        }
        if (0 !== $this->Port) {
            $out->Port = $this->Port;
        }
        if ([] !== $this->Ports) {
            $out->Ports = $this->Ports;
        }
        if ('' !== $this->Address) {
            $out->Address = $this->Address;
        }
        if ('' !== $this->SocketPath) {
            $out->SocketPath = $this->SocketPath;
        }
        if (null !== $this->TaggedAddresses && [] !== $this->TaggedAddresses) {
            $out->TaggedAddresses = $this->TaggedAddresses;
        }
        if ($this->EnableTagOverride) {
            $out->EnableTagOverride = $this->EnableTagOverride;
        }
        if (null !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        if (null !== $this->Weights) {
            $out->Weights = $this->Weights;
        }
        $out->Check = $this->Check;
        $out->Checks = $this->Checks;
        if (null !== $this->Proxy) {
            $out->Proxy = $this->Proxy;
        }
        if (null !== $this->Connect) {
            $out->Connect = $this->Connect;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if (null !== $this->Locality) {
            $out->Locality = $this->Locality;
        }
        return $out;
    }

    public function __toString(): string
    {
        return $this->Name;
    }
}
