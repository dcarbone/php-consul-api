<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

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
use DCarbone\PHPConsulAPI\Health\ServiceEntry;
use DCarbone\PHPConsulAPI\Transcoding;

class PreparedQueryExecuteResponse extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_NODES     => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ServiceEntry::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
        self::FIELD_DNS       => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => QueryDNSOptions::class,
        ],
        self::FIELD_NAMESPACE => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_NAMESPACE = 'Namespace';
    private const FIELD_NODES     = 'Nodes';
    private const FIELD_DNS       = 'DNS';

    public string $Service = '';
    public string $Namespace = '';
    public array $Nodes = [];
    public QueryDNSOptions $DNS;
    public string $Datacenter = '';
    public int $Failovers = 0;

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->DNS)) {
            $this->DNS = new QueryDNSOptions(null);
        }
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

    public function getNodes(): array
    {
        return $this->Nodes;
    }

    public function setNodes(array $Nodes): self
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
}
