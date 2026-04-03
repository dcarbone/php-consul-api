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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class ServiceQuery extends AbstractType
{
    public string $Service;
    public string $Namespace;
    public string $Near;
    /** @var array<string> */
    public array $Tags;
    /** @var array<string> */
    public array $IgnoreCheckIDs;
    public QueryDatacenterOptions $Failover;
    public bool $OnlyPassing;
    /** @var array<string, string> */
    public array $NodeMeta;
    /** @var array<string, string> */
    public array $ServiceMeta;
    public bool $Connect;

    /**
     * @param array<string> $Tags
     * @param array<string> $IgnoreCheckIDs
     * @param array<string, string> $NodeMeta
     * @param array<string, string> $ServiceMeta
     */
    public function __construct(
        string $Service = '',
        string $Namespace = '',
        string $Near = '',
        array $Tags = [],
        array $IgnoreCheckIDs = [],
        null|QueryDatacenterOptions $Failover = null,
        bool $OnlyPassing = false,
        array $NodeMeta = [],
        array $ServiceMeta = [],
        bool $Connect = false,
    ) {
        $this->Service = $Service;
        $this->Namespace = $Namespace;
        $this->Near = $Near;
        $this->setTags(...$Tags);
        $this->setIgnoreCheckIDs(...$IgnoreCheckIDs);
        $this->Failover = $Failover ?? new QueryDatacenterOptions();
        $this->OnlyPassing = $OnlyPassing;
        $this->NodeMeta = $NodeMeta;
        $this->ServiceMeta = $ServiceMeta;
        $this->Connect = $Connect;
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

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->Tags;
    }

    public function setTags(string ...$Tags): self
    {
        $this->Tags = $Tags;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getIgnoreCheckIDs(): array
    {
        return $this->IgnoreCheckIDs;
    }

    public function setIgnoreCheckIDs(string ...$IgnoreCheckIDs): self
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

    /**
     * @return array<string, string>
     */
    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    /**
     * @param array<string, string> $NodeMeta
     */
    public function setNodeMeta(array $NodeMeta): self
    {
        $this->NodeMeta = $NodeMeta;
        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getServiceMeta(): array
    {
        return $this->ServiceMeta;
    }

    /**
     * @param array<string, string> $ServiceMeta
     */
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Failover' === $k) {
                $n->Failover = QueryDatacenterOptions::jsonUnserialize($v);
            } elseif ('Tags' === $k) {
                $n->setTags(...$v);
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
        $out->Near = $this->Near;
        $out->Tags = $this->Tags;
        $out->IgnoreCheckIDs = $this->IgnoreCheckIDs;
        $out->Failover = $this->Failover;
        $out->OnlyPassing = $this->OnlyPassing;
        $out->NodeMeta = $this->NodeMeta;
        $out->ServiceMeta = $this->ServiceMeta;
        $out->Connect = $this->Connect;
        return $out;
    }
}
