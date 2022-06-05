<?php

declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\HasSettableStringTags;
use DCarbone\PHPConsulAPI\HasStringTags;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ServiceQuery
 */
class ServiceQuery extends AbstractModel
{
    use HasSettableStringTags;
    use HasStringTags;

    protected const FIELDS = [
        self::FIELD_NAMESPACE => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_FAILOVER  => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => QueryDatacenterOptions::class,
        ],
    ];

    private const FIELD_NAMESPACE = 'Namespace';
    private const FIELD_FAILOVER  = 'Failover';

    /** @var string */
    public string $Service = '';
    /** @var string */
    public string $Namespace = '';
    /** @var string */
    public string $Near = '';
    /** @var string[] */
    public array $IgnoreCheckIDs = [];
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions */
    public QueryDatacenterOptions $Failover;
    /** @var bool */
    public bool $OnlyPassing = false;
    /** @var array */
    public array $NodeMeta = [];
    /** @var array */
    public array $ServiceMeta = [];
    /** @var bool */
    public bool $Connect = false;

    /**
     * ServiceQuery constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Failover)) {
            $this->Failover = new QueryDatacenterOptions(null);
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
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
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
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getNear(): string
    {
        return $this->Near;
    }

    /**
     * @param string $Near
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setNear(string $Near): self
    {
        $this->Near = $Near;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getIgnoreCheckIDs(): array
    {
        return $this->IgnoreCheckIDs;
    }

    /**
     * @param string[] $IgnoreCheckIDs
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setIgnoreCheckIDs(array $IgnoreCheckIDs): self
    {
        $this->IgnoreCheckIDs = $IgnoreCheckIDs;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions
     */
    public function getFailover(): QueryDatacenterOptions
    {
        return $this->Failover;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions $Failover
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setFailover(QueryDatacenterOptions $Failover): self
    {
        $this->Failover = $Failover;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOnlyPassing(): bool
    {
        return $this->OnlyPassing;
    }

    /**
     * @param bool $OnlyPassing
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setOnlyPassing(bool $OnlyPassing): self
    {
        $this->OnlyPassing = $OnlyPassing;
        return $this;
    }

    /**
     * @return array
     */
    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    /**
     * @param array $NodeMeta
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setNodeMeta(array $NodeMeta): self
    {
        $this->NodeMeta = $NodeMeta;
        return $this;
    }

    /**
     * @return array
     */
    public function getServiceMeta(): array
    {
        return $this->ServiceMeta;
    }

    /**
     * @param array $ServiceMeta
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setServiceMeta(array $ServiceMeta): self
    {
        $this->ServiceMeta = $ServiceMeta;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConnect(): bool
    {
        return $this->Connect;
    }

    /**
     * @param bool $Connect
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setConnect(bool $Connect): self
    {
        $this->Connect = $Connect;
        return $this;
    }
}
