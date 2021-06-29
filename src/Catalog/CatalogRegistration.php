<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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
use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class CatalogRegistration
 */
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

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Node = '';
    /** @var string */
    public string $Address = '';
    /** @var \DCarbone\PHPConsulAPI\FakeMap */
    public FakeMap $TaggedAddresses;
    /** @var \DCarbone\PHPConsulAPI\FakeMap */
    public FakeMap $NodeMeta;
    /** @var string */
    public string $Datacenter = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService|null */
    public ?AgentService $Service = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentCheck|null */
    public ?AgentCheck $Check = null;
    /** @var \DCarbone\PHPConsulAPI\Health\HealthChecks */
    public HealthChecks $checks;
    /** @var bool */
    public bool $SkipNodeUpdate = false;

    /**
     * CatalogRegistration constructor.
     * @param array|null $data
     */
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

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\FakeMap
     */
    public function getTaggedAddresses(): FakeMap
    {
        return $this->TaggedAddresses;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\FakeMap $TaggedAddresses
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setTaggedAddresses(FakeMap $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\FakeMap
     */
    public function getNodeMeta(): FakeMap
    {
        return $this->NodeMeta;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\FakeMap $NodeMeta
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setNodeMeta(FakeMap $NodeMeta): self
    {
        $this->NodeMeta = $NodeMeta;
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
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService|null
     */
    public function getService(): ?AgentService
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentService|null $Service
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setService(?AgentService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck|null
     */
    public function getCheck(): ?AgentCheck
    {
        return $this->Check;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheck|null $Check
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setCheck(?AgentCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthChecks
     */
    public function getChecks(): HealthChecks
    {
        return $this->checks;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Health\HealthChecks $checks
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setChecks(HealthChecks $checks): self
    {
        $this->checks = $checks;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSkipNodeUpdate(): bool
    {
        return $this->SkipNodeUpdate;
    }

    /**
     * @param bool $SkipNodeUpdate
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration
     */
    public function setSkipNodeUpdate(bool $SkipNodeUpdate): self
    {
        $this->SkipNodeUpdate = $SkipNodeUpdate;
        return $this;
    }
}
