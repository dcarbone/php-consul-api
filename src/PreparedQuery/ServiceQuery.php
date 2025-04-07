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
use DCarbone\PHPConsulAPI\HasSettableStringTags;
use DCarbone\PHPConsulAPI\HasStringTags;
use DCarbone\PHPConsulAPI\Transcoding;

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

    public string $Service = '';
    public string $Namespace = '';
    public string $Near = '';
    public array $IgnoreCheckIDs = [];
    public QueryDatacenterOptions $Failover;
    public bool $OnlyPassing = false;
    public array $NodeMeta = [];
    public array $ServiceMeta = [];
    public bool $Connect = false;

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Failover)) {
            $this->Failover = new QueryDatacenterOptions(null);
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

    public function getNear(): string
    {
        return $this->Near;
    }

    public function setNear(string $Near): self
    {
        $this->Near = $Near;
        return $this;
    }

    public function getIgnoreCheckIDs(): array
    {
        return $this->IgnoreCheckIDs;
    }

    public function setIgnoreCheckIDs(array $IgnoreCheckIDs): self
    {
        $this->IgnoreCheckIDs = $IgnoreCheckIDs;
        return $this;
    }

    public function getFailover(): QueryDatacenterOptions
    {
        return $this->Failover;
    }

    public function setFailover(QueryDatacenterOptions $Failover): self
    {
        $this->Failover = $Failover;
        return $this;
    }

    public function isOnlyPassing(): bool
    {
        return $this->OnlyPassing;
    }

    public function setOnlyPassing(bool $OnlyPassing): self
    {
        $this->OnlyPassing = $OnlyPassing;
        return $this;
    }

    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    public function setNodeMeta(array $NodeMeta): self
    {
        $this->NodeMeta = $NodeMeta;
        return $this;
    }

    public function getServiceMeta(): array
    {
        return $this->ServiceMeta;
    }

    public function setServiceMeta(array $ServiceMeta): self
    {
        $this->ServiceMeta = $ServiceMeta;
        return $this;
    }

    public function isConnect(): bool
    {
        return $this->Connect;
    }

    public function setConnect(bool $Connect): self
    {
        $this->Connect = $Connect;
        return $this;
    }
}
