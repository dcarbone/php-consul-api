<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class PreparedQueryExecuteResponse
 */
class PreparedQueryExecuteResponse extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_NODES     => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => ServiceEntry::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
        self::FIELD_DNS       => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => QueryDNSOptions::class,
        ],
        self::FIELD_NAMESPACE => Hydration::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_NAMESPACE = 'Namespace';
    private const FIELD_NODES     = 'Nodes';
    private const FIELD_DNS       = 'DNS';

    /** @var string */
    public string $Service = '';
    /** @var string */
    public string $Namespace = '';
    /** @var \DCarbone\PHPConsulAPI\Health\ServiceEntry[] */
    public array $Nodes = [];
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions */
    public QueryDNSOptions $DNS;
    /** @var string */
    public string $Datacenter = '';
    /** @var int */
    public int $Failovers = 0;

    /**
     * PreparedQueryExecuteResponse constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->DNS)) {
            $this->DNS = new QueryDNSOptions(null);
        }
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->Service;
    }

    /**
     * @param string $Service
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryExecuteResponse
     */
    public function setService(string $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $Namespace
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryExecuteResponse
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntry[]
     */
    public function getNodes(): array
    {
        return $this->Nodes;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Health\ServiceEntry[] $Nodes
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryExecuteResponse
     */
    public function setNodes(array $Nodes): self
    {
        $this->Nodes = $Nodes;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions
     */
    public function getDNS(): QueryDNSOptions
    {
        return $this->DNS;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions $DNS
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryExecuteResponse
     */
    public function setDNS(QueryDNSOptions $DNS): self
    {
        $this->DNS = $DNS;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    /**
     * @param string $Datacenter
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryExecuteResponse
     */
    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return int
     */
    public function getFailovers(): int
    {
        return $this->Failovers;
    }

    /**
     * @param int $Failovers
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryExecuteResponse
     */
    public function setFailovers(int $Failovers): self
    {
        $this->Failovers = $Failovers;
        return $this;
    }
}
