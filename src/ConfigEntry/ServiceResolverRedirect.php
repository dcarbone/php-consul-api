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

class ServiceResolverRedirect extends AbstractType
{
    public string $Service;
    public string $ServiceSubset;
    public string $Namespace;
    public string $Partition;
    public string $Datacenter;
    public string $Peer;
    public string $SamenessGroup;

    public function __construct(
        string $Service = '',
        string $ServiceSubset = '',
        string $Namespace = '',
        string $Partition = '',
        string $Datacenter = '',
        string $Peer = '',
        string $SamenessGroup = ''
    ) {
        $this->Service = $Service;
        $this->ServiceSubset = $ServiceSubset;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        $this->Datacenter = $Datacenter;
        $this->Peer = $Peer;
        $this->SamenessGroup = $SamenessGroup;
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

    public function getServiceSubset(): string
    {
        return $this->ServiceSubset;
    }

    public function setServiceSubset(string $ServiceSubset): self
    {
        $this->ServiceSubset = $ServiceSubset;
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

    public function getPeer(): string
    {
        return $this->Peer;
    }

    public function setPeer(string $Peer): self
    {
        $this->Peer = $Peer;
        return $this;
    }

    public function getSamenessGroup(): string
    {
        return $this->SamenessGroup;
    }

    public function setSamenessGroup(string $SamenessGroup): self
    {
        $this->SamenessGroup = $SamenessGroup;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('service_subset' === $k) {
                $n->ServiceSubset = $v;
            } elseif ('sameness_group' === $k) {
                $n->SamenessGroup = $v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ('' !== $this->Service) {
            $out->Service = $this->Service;
        }
        if ('' !== $this->ServiceSubset) {
            $out->service_subset = $this->ServiceSubset;
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
        if ('' !== $this->Peer) {
            $out->Peer = $this->Peer;
        }
        if ('' !== $this->SamenessGroup) {
            $out->sameness_group = $this->SamenessGroup;
        }
        return $out;
    }
}
