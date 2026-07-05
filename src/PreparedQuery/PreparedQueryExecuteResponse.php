<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

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

use DCarbone\PHPConsulAPI\Health\ServiceEntry;
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class PreparedQueryExecuteResponse extends AbstractType
{
    public string $Service;
    public string $Namespace;
    /** @var array<ServiceEntry> */
    public array $Nodes;
    public QueryDNSOptions $DNS;
    public string $Datacenter;
    public int $Failovers;

    /**
     * @param array<ServiceEntry> $Nodes
     */
    public function __construct(
        string $Service = '',
        string $Namespace = '',
        array $Nodes = [],
        null|QueryDNSOptions $DNS = null,
        string $Datacenter = '',
        int $Failovers = 0,
    ) {
        $this->Service = $Service;
        $this->Namespace = $Namespace;
        $this->setNodes(...$Nodes);
        $this->DNS = $DNS ?? new QueryDNSOptions();
        $this->Datacenter = $Datacenter;
        $this->Failovers = $Failovers;
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

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return array<ServiceEntry>
     */
    public function getNodes(): array
    {
        return $this->Nodes;
    }

    public function setNodes(ServiceEntry ...$Nodes): self
    {
        $this->Nodes = $Nodes;
        return $this;
    }

    public function getDNS(): QueryDNSOptions
    {
        return $this->DNS;
    }

    public function setDNS(QueryDNSOptions $DNS): self
    {
        $this->DNS = $DNS;
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

    public function getFailovers(): int
    {
        return $this->Failovers;
    }

    public function setFailovers(int $Failovers): self
    {
        $this->Failovers = $Failovers;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Nodes' === $k) {
                $n->Nodes = [];
                foreach ($v as $nv) {
                    $n->Nodes[] = ServiceEntry::jsonUnserialize($nv);
                }
            } elseif ('DNS' === $k) {
                $n->DNS = QueryDNSOptions::jsonUnserialize($v);
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
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        $out->Nodes = $this->Nodes;
        $out->DNS = $this->DNS;
        $out->Datacenter = $this->Datacenter;
        $out->Failovers = $this->Failovers;
        return $out;
    }
}
