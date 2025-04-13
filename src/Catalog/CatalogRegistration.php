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
use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Transcoding;

class CatalogRegistration extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_TAGGED_ADDRESSES => Transcoding::MAP_FIELD,
        self::FIELD_NODE_META        => Transcoding::MAP_FIELD,
        self::FIELD_SERVICE          => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => AgentService::class,
        ],
        self::FIELD_CHECK   => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => AgentCheck::class,
        ],
        self::FIELD_CHECKS  => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => HealthChecks::class,
        ],
    ];

    private const FIELD_TAGGED_ADDRESSES = 'TaggedAddresses';
    private const FIELD_NODE_META        = 'NodeMeta';
    private const FIELD_SERVICE          = 'Service';
    private const FIELD_CHECK            = 'Check';
    private const FIELD_CHECKS           = 'Checks';

    public string $ID;
    public string $Node;
    public string $Address;
    public FakeMap $TaggedAddresses;
    public FakeMap $NodeMeta;
    public string $Datacenter;
    public ?AgentService $Service = null;
    public ?AgentCheck $Check = null;
    public HealthChecks $Checks;
    public bool $SkipNodeUpdate;

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Checks)) {
            $this->Checks = new HealthChecks(null);
        }
        if (!isset($this->TaggedAddresses)) {
            $this->TaggedAddresses = new FakeMap(null);
        }
        if (!isset($this->NodeMeta)) {
            $this->NodeMeta = new FakeMap(null);
        }
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getNode(): string
    {
        return $this->Node;
    }

    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    public function getTaggedAddresses(): FakeMap
    {
        return $this->TaggedAddresses;
    }

    public function setTaggedAddresses(FakeMap $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    public function getNodeMeta(): FakeMap
    {
        return $this->NodeMeta;
    }

    public function setNodeMeta(FakeMap $NodeMeta): self
    {
        $this->NodeMeta = $NodeMeta;
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

    public function getService(): ?AgentService
    {
        return $this->Service;
    }

    public function setService(?AgentService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getCheck(): ?AgentCheck
    {
        return $this->Check;
    }

    public function setCheck(?AgentCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }

    public function getChecks(): HealthChecks
    {
        return $this->Checks;
    }

    public function setChecks(HealthChecks $checks): self
    {
        $this->Checks = $checks;
        return $this;
    }

    public function isSkipNodeUpdate(): bool
    {
        return $this->SkipNodeUpdate;
    }

    public function setSkipNodeUpdate(bool $SkipNodeUpdate): self
    {
        $this->SkipNodeUpdate = $SkipNodeUpdate;
        return $this;
    }
}
