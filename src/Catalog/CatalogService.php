<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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
use DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Peering\Locality;

class CatalogService extends AbstractModel
{
    public string $ID;
    public string $Node;
    public string $Address;
    public string $Datacenter;
    public null|\stdClass $TaggedAddresses;
    public null|\stdClass $NodeMeta;
    public string $ServiceID;
    public string $ServiceName;
    public string $ServiceAddress;
    public null|\stdClass $ServiceTaggedAddresses;
    /** @var array<string> */
    public array $ServiceTags;
    public null|\stdClass $ServiceMeta;
    public int $ServicePort;
    public Weights $ServiceWeights;
    public bool $ServiceEnableTagOverride;
    public null|AgentServiceConnectProxyConfig $ServiceProxy;
    public null|Locality $ServiceLocality;
    public int $CreateIndex;
    public HealthChecks $Checks;
    public int $ModifyIndex;
    public string $Namespace;
    public string $Partition;

    /**
     * @param array<string> $ServiceTags
     */
    public function __construct(
        string $ID = '',
        string $Node = '',
        string $Address = '',
        string $Datacenter = '',
        null|\stdclass $TaggedAddresses = null,
        null|\stdclass $NodeMeta = null,
        string $ServiceID = '',
        string $ServiceName = '',
        string $ServiceAddress = '',
        null|\stdclass $ServiceTaggedAddresses = null,
        array $ServiceTags = [],
        null|\stdclass $ServiceMeta = null,
        int $ServicePort = 0,
        null|Weights $ServiceWeights = null,
        bool $ServiceEnableTagOverride = false,
        null|AgentServiceConnectProxyConfig $ServiceProxy = null,
        null|Locality $ServiceLocality = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        null|HealthChecks $Checks = null,
        string $Namespace = '',
        string $Partition = ''
    ) {
        $this->ID = $ID;
        $this->Node = $Node;
        $this->Address = $Address;
        $this->Datacenter = $Datacenter;
        $this->TaggedAddresses = $TaggedAddresses;
        $this->NodeMeta = $NodeMeta;
        $this->ServiceID = $ServiceID;
        $this->ServiceName = $ServiceName;
        $this->ServiceAddress = $ServiceAddress;
        $this->ServiceTaggedAddresses = $ServiceTaggedAddresses;
        $this->setServiceTags(...$ServiceTags);
        $this->setServiceTaggedAddresses($ServiceTaggedAddresses);
        $this->ServiceMeta = $ServiceMeta;
        $this->ServicePort = $ServicePort;
        $this->ServiceWeights = $ServiceWeights ?? new Weights();
        $this->ServiceEnableTagOverride = $ServiceEnableTagOverride;
        $this->ServiceProxy = $ServiceProxy;
        $this->ServiceLocality = $ServiceLocality;
        $this->CreateIndex = $CreateIndex;
        $this->Checks = $Checks ?? new HealthChecks();
        $this->ModifyIndex = $ModifyIndex;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
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

    public function getNode(): string
    {
        return $this->Node;
    }

    public function setNode(string $Node): self
    {
        $this->Node = $Node;
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

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    public function getTaggedAddresses(): null|\stdClass
    {
        return $this->TaggedAddresses;
    }

    public function setTaggedAddresses(null|\stdClass $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    public function getNodeMeta(): \stdClass
    {
        return $this->NodeMeta;
    }

    public function setNodeMeta(null|\stdClass $NodeMeta): self
    {
        $this->NodeMeta = $NodeMeta;
        return $this;
    }

    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    public function setServiceID(string $ServiceID): self
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    public function setServiceName(string $ServiceName): self
    {
        $this->ServiceName = $ServiceName;
        return $this;
    }

    public function getServiceAddress(): string
    {
        return $this->ServiceAddress;
    }

    public function setServiceAddress(string $ServiceAddress): self
    {
        $this->ServiceAddress = $ServiceAddress;
        return $this;
    }

    public function getServiceTaggedAddresses(): \stdClass
    {
        return $this->ServiceTaggedAddresses;
    }

    public function setServiceTaggedAddresses(null|\stdClass $ServiceTaggedAddresses): self
    {
        if (null === $ServiceTaggedAddresses) {
            $this->ServiceTaggedAddresses = null;
            return $this;
        }
        $this->ServiceTaggedAddresses = new \stdClass();
        foreach ($ServiceTaggedAddresses as $k => $v) {
            if ($v instanceof ServiceAddress) {
                $this->ServiceTaggedAddresses->{$k} = $v;
            } else {
                $this->ServiceTaggedAddresses->{$k} = ServiceAddress::jsonUnserialize((object)$v);
            }
        }
        return $this;
    }

    /**
     * @return string[]
     */
    public function getServiceTags(): array
    {
        return $this->ServiceTags;
    }

    public function setServiceTags(string ...$ServiceTags): self
    {
        $this->ServiceTags = $ServiceTags;
        return $this;
    }

    public function getServiceMeta(): \stdClass
    {
        return $this->ServiceMeta;
    }

    public function setServiceMeta(null|\stdClass $ServiceMeta): self
    {
        $this->ServiceMeta = $ServiceMeta;
        return $this;
    }

    public function getServicePort(): int
    {
        return $this->ServicePort;
    }

    public function setServicePort(int $ServicePort): self
    {
        $this->ServicePort = $ServicePort;
        return $this;
    }

    public function getServiceWeights(): Weights
    {
        return $this->ServiceWeights;
    }

    public function setServiceWeights(Weights $ServiceWeights): self
    {
        $this->ServiceWeights = $ServiceWeights;
        return $this;
    }

    public function isServiceEnableTagOverride(): bool
    {
        return $this->ServiceEnableTagOverride;
    }

    public function setServiceEnableTagOverride(bool $ServiceEnableTagOverride): self
    {
        $this->ServiceEnableTagOverride = $ServiceEnableTagOverride;
        return $this;
    }

    public function getServiceProxy(): null|AgentServiceConnectProxyConfig
    {
        return $this->ServiceProxy;
    }

    public function setServiceProxy(null|AgentServiceConnectProxyConfig $ServiceProxy): self
    {
        $this->ServiceProxy = $ServiceProxy;
        return $this;
    }

    public function getServiceLocality(): null|Locality
    {
        return $this->ServiceLocality;
    }

    public function setServiceLocality(null|Locality $ServiceLocality): self
    {
        $this->ServiceLocality = $ServiceLocality;
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

    public function getChecks(): HealthChecks
    {
        return $this->Checks;
    }

    public function setChecks(HealthChecks $Checks): self
    {
        $this->Checks = $Checks;
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

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('TaggedAddresses' === $k) {
                $n->settaggedAddresses($v);
            } elseif ('NodeMeta' === $k) {
                $n->setnodemeta($v);
            } elseif ('ServiceTaggedAddresses' === $k) {
                $n->setservicetaggedaddresses($v);
            } elseif ('Weights' === $k) {
                $n->ServiceWeights = Weights::jsonUnserialize($v);
            } elseif ('ServiceProxy' === $k) {
                if (null !== $v) {
                    $n->ServiceProxy = AgentServiceConnectProxyConfig::jsonUnserialize($v);
                }
            } elseif ('ServiceLocality' === $k) {
                if (null !== $v) {
                    $n->ServiceLocality = Locality::jsonUnserialize($v);
                }
            } elseif ('Checks' === $k) {
                if (null !== $v) {
                    $n->Checks = HealthChecks::jsonUnserialize($v);
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
        $out->ID = $this->ID;
        $out->Node = $this->Node;
        $out->Address = $this->Address;
        $out->Datacenter = $this->Datacenter;
        $out->TaggedAddresses = $this->TaggedAddresses;
        $out->NodeMeta = $this->NodeMeta;
        $out->ServiceID = $this->ServiceID;
        $out->ServiceName = $this->ServiceName;
        $out->ServiceAddress = $this->ServiceAddress;
        $out->ServiceTaggedAddresses = $this->ServiceTaggedAddresses;
        $out->ServiceTags = $this->ServiceTags;
        $out->ServiceMeta = $this->ServiceMeta;
        $out->ServicePort = $this->ServicePort;
        $out->ServiceWeights = $this->ServiceWeights;
        $out->ServiceEnableTagOverride = $this->ServiceEnableTagOverride;
        $out->ServiceProxy = $this->ServiceProxy;
        if (null !== $this->ServiceLocality) {
            $out->ServiceLocality = $this->ServiceLocality;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->Checks = $this->Checks;
        $out->ModifyIndex = $this->ModifyIndex;
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        return $out;
    }
}
