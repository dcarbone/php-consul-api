<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class ServiceQuery
 */
class ServiceQuery extends AbstractModel
{
    use HasSettableStringTags;
    use HasStringTags;

    /** @var string */
    public $Service = '';
    /** @var string */
    public $Namespace = '';
    /** @var string */
    public $Near = '';
    /** @var string[] */
    public $IgnoreCheckIDs = null;
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions */
    public $Failover = null;
    /** @var bool */
    public $OnlyPassing = false;
    /** @var array|null */
    public $NodeMeta = null;
    /** @var array|null */
    public $ServiceMeta = null;
    /** @var bool */
    public $Connect = false;

    /**
     * ServiceQuery constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        if (!($this->Failover instanceof QueryDatacenterOptions)) {
            $this->Failover = new QueryDatacenterOptions((array) $this->Failover);
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
     * @param string $service
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setService(string $service): self
    {
        $this->Service = $service;
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
     * @param string $namespace
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setNamespace(string $namespace): self
    {
        $this->Namespace = $namespace;
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
     * @param string $near
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setNear(string $near): self
    {
        $this->Near = $near;
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
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions $failover
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setFailover(QueryDatacenterOptions $failover): self
    {
        $this->Failover = $failover;
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
     * @param bool $onlyPassing
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setOnlyPassing(bool $onlyPassing): self
    {
        $this->OnlyPassing = $onlyPassing;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getIgnoreCheckIDs(): ?array
    {
        return $this->IgnoreCheckIDs;
    }

    /**
     * @param string[] $ignoreCheckIDs
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setIgnoreCheckIDs(?array $ignoreCheckIDs): self
    {
        $this->IgnoreCheckIDs = $ignoreCheckIDs;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getNodeMeta(): ?array
    {
        return $this->NodeMeta;
    }

    /**
     * @param array|null $nodeMeta
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setNodeMeta(?array $nodeMeta): self
    {
        $this->NodeMeta = $nodeMeta;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getServiceMeta(): ?array
    {
        return $this->ServiceMeta;
    }

    /**
     * @param array|null $serviceMeta
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setServiceMeta(?array $serviceMeta): self
    {
        $this->ServiceMeta = $serviceMeta;
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
     * @param bool $connect
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function setConnect(bool $connect): self
    {
        $this->Connect = $connect;
        return $this;
    }
}
