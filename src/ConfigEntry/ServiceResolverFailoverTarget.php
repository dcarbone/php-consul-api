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

class ServiceResolverFailoverTarget extends AbstractType
{
    public string $Service;
    public string $ServiceSubset;
    public string $Partition;
    public string $Namespace;
    public string $Datacenter;
    public string $Peer;

    public function __construct(
        string $Service = '',
        string $ServiceSubset = '',
        string $Partition = '',
        string $Namespace = '',
        string $Datacenter = '',
        string $Peer = ''
    ) {
        $this->Service = $Service;
        $this->ServiceSubset = $ServiceSubset;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->Datacenter = $Datacenter;
        $this->Peer = $Peer;
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('service_subset' === $k) {
                $n->ServiceSubset = $v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Service = $this->Service;
        $out->ServiceSubset = $this->ServiceSubset;
        $out->Partition = $this->Partition;
        $out->Namespace = $this->Namespace;
        $out->Datacenter = $this->Datacenter;
        $out->Peer = $this->Peer;
        return $out;
    }
}
