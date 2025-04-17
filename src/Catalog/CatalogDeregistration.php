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

class CatalogDeregistration extends AbstractModel
{
    public string $Node;
    public string $Address;
    public string $Datacenter;
    public string $ServiceID;
    public string $CheckID;
    public string $Namespace;
    public string $Partition;

    /**
     * @param array<string, mixed>|null $data
     * @param string $Node
     * @param string $Address
     * @param string $Datacenter
     * @param string $ServiceID
     * @param string $CheckID
     * @param string $Namespace
     * @param string $Partition
     */
    public function __construct(
        null|array $data = null, // Deprecated, do not use
        string $Node = '',
        string $Address = '',
        string $Datacenter = '',
        string $ServiceID = '',
        string $CheckID = '',
        string $Namespace = '',
        string $Partition = ''
    ) {
        $this->Node = $Node;
        $this->Address = $Address;
        $this->Datacenter = $Datacenter;
        $this->ServiceID = $ServiceID;
        $this->CheckID = $CheckID;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
        }
    }

    public function getNode(): string
    {
        return $this->Node;
    }

    public function setNode(string $node): self
    {
        $this->Node = $node;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->Address;
    }

    public function setAddress(string $address): self
    {
        $this->Address = $address;
        return $this;
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $datacenter): self
    {
        $this->Datacenter = $datacenter;
        return $this;
    }

    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    public function setServiceID(string $serviceID): self
    {
        $this->ServiceID = $serviceID;
        return $this;
    }

    public function getCheckID(): string
    {
        return $this->CheckID;
    }

    public function setCheckID(string $checkID): self
    {
        $this->CheckID = $checkID;
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->Node = $this->Node;
        if ('' !== $this->Address) {
            $out->Address = $this->Address;
        }
        $out->Datacenter = $this->Datacenter;
        $out->ServiceID = $this->ServiceID;
        $out->CheckID = $this->CheckID;
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        return $out;
    }
}
