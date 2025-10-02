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
use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\PHPLib\Types\NodeMetaField;
use DCarbone\PHPConsulAPI\Peering\Locality;
use DCarbone\PHPConsulAPI\PHPLib\Types\TaggedAddressField;

class CatalogRegistration extends AbstractModel
{
    use TaggedAddressField;
    use NodeMetaField;

    public string $ID;
    public string $Node;
    public string $Address;
    public string $Datacenter;
    public null|AgentService $Service;
    public null|AgentCheck $Check;
    public null|HealthChecks $Checks;
    public bool $SkipNodeUpdate;
    public string $Partition;
    public null|Locality $Locality;

    /**
     * @param string $ID
     * @param string $Node
     * @param string $Address
     * @param array<string,string> $TaggedAddresses
     * @param array<string,string> $NodeMeta
     * @param string $Datacenter
     * @param \DCarbone\PHPConsulAPI\Agent\AgentService|null $Service
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheck|null $Check
     * @param \DCarbone\PHPConsulAPI\Health\HealthChecks|null $Checks
     * @param bool $SkipNodeUpdate
     * @param string $Partition
     * @param \DCarbone\PHPConsulAPI\Peering\Locality|null $Locality
     */
    public function __construct(
        string $ID = '',
        string $Node = '',
        string $Address = '',
        array $TaggedAddresses = [],
        array $NodeMeta = [],
        string $Datacenter = '',
        null|AgentService $Service = null,
        null|AgentCheck $Check = null,
        null|HealthChecks $Checks = null,
        bool $SkipNodeUpdate = false,
        string $Partition = '',
        null|Locality $Locality = null,
    ) {
        $this->ID = $ID;
        $this->Node = $Node;
        $this->Address = $Address;
        $this->setTaggedAddresses($TaggedAddresses);
        $this->setNodeMeta($NodeMeta);
        $this->Datacenter = $Datacenter;
        $this->Service = $Service;
        $this->Check = $Check;
        $this->Checks = $Checks;
        $this->SkipNodeUpdate = $SkipNodeUpdate;
        $this->Partition = $Partition;
        $this->Locality = $Locality;
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

    public function getService(): null|AgentService
    {
        return $this->Service;
    }

    public function setService(null|AgentService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getCheck(): null|AgentCheck
    {
        return $this->Check;
    }

    public function setCheck(null|AgentCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }

    public function getChecks(): HealthChecks
    {
        return $this->Checks;
    }

    public function setChecks(HealthChecks $checks): self
    {
        $this->Checks = $checks;
        return $this;
    }

    public function isSkipNodeUpdate(): bool
    {
        return $this->SkipNodeUpdate;
    }

    public function setSkipNodeUpdate(bool $SkipNodeUpdate): self
    {
        $this->SkipNodeUpdate = $SkipNodeUpdate;
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
        foreach ($decoded as $k => $v) {
            if ('TaggedAddresses' === $k) {
                $n->setTaggedAddresses($v);
            } elseif ('NodeMeta' === $k) {
                $n->setNodeMeta($v);
            } elseif ('Service' === $k) {
                $n->Service = null === $v ? null : AgentService::jsonUnserialize($v);
            } elseif ('Check' === $k) {
                $n->Check = null === $v ? null : AgentCheck::jsonUnserialize($v);
            } elseif ('Checks' === $k) {
                $n->Checks = null === $v ? null : HealthChecks::jsonUnserialize($v);
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
        $out->ID = $this->ID;
        $out->Node = $this->Node;
        $out->Address = $this->Address;
        $out->TaggedAddresses = $this->getTaggedAddresses();
        $out->NodeMeta = $this->getNodeMeta();
        $out->Datacenter = $this->Datacenter;
        $out->Service = $this->Service;
        $out->Check = $this->Check;
        $out->Checks = $this->Checks;
        $out->SkipNodeUpdate = $this->SkipNodeUpdate;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if (null !== $this->Locality) {
            $out->Locality = $this->Locality;
        }
        return $out;
    }
}
