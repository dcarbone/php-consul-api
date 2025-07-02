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
use DCarbone\PHPConsulAPI\Peering\Locality;

class Node extends AbstractModel
{
    public string $ID;
    public string $Node;
    public string $Address;
    public string $Datacenter;
    public null|\stdClass $TaggedAddresses;
    public null|\stdClass $Meta;
    public int $CreateIndex;
    public int $ModifyIndex;
    public string $Partition;
    public string $PeerName;
    public null|Locality $Locality;

    /**
     * @param array<string,mixed>|null $data
     */
    public function __construct(
        string $ID = '',
        string $Node = '',
        string $Address = '',
        string $Datacenter = '',
        null|\stdClass $TaggedAddresses = null,
        null|\stdClass $Meta = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        string $Partition = '',
        string $PeerName = '',
        null|Locality $Locality = null
    ) {
        $this->ID = $ID;
        $this->Node = $Node;
        $this->Address = $Address;
        $this->Datacenter = $Datacenter;
        $this->TaggedAddresses = $TaggedAddresses ?? new \stdClass();
        $this->Meta = $Meta ?? new \stdClass();
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->Partition = $Partition;
        $this->PeerName = $PeerName;
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

    public function getTaggedAddresses(): null|\stdClass
    {
        return $this->TaggedAddresses;
    }

    public function setTaggedAddresses(null|\stdClass $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    public function getMeta(): null|\stdClass
    {
        return $this->Meta;
    }

    public function setMeta(null|\stdClass $Meta): self
    {
        $this->Meta = $Meta;
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

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
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
            if ('Locality' === $k) {
                $n->Locality = Locality::jsonUnserialize($v);
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
        $out->Meta = $this->Meta;
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->PeerName) {
            $out->PeerName = $this->PeerName;
        }
        if (null !== $this->Locality) {
            $out->Locality = $this->Locality;
        }
        return $out;
    }
}
