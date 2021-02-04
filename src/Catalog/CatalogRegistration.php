<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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
use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class CatalogRegistration
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogRegistration extends AbstractModel
{
    private const FIELD_SERVICE = 'Service';
    private const FIELD_CHECK   = 'Check';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Node = '';
    /** @var string */
    public string $Address = '';
    /** @var array */
    public array $TaggedAddresses = [];
    /** @var array */
    public array $NodeMeta = [];
    /** @var string */
    public string $Datacenter = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService|null */
    public ?AgentService $Service = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentCheck|null */
    public ?AgentCheck $Check = null;

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_SERVICE => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => AgentService::class,
        ],
        self::FIELD_CHECK   => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => AgentCheck::class,
        ],
    ];

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return CatalogRegistration
     */
    public function setID(string $ID): CatalogRegistration
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
     * @return CatalogRegistration
     */
    public function setNode(string $Node): CatalogRegistration
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
     * @return CatalogRegistration
     */
    public function setAddress(string $Address): CatalogRegistration
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return array
     */
    public function getTaggedAddresses(): array
    {
        return $this->TaggedAddresses;
    }

    /**
     * @param array $TaggedAddresses
     * @return CatalogRegistration
     */
    public function setTaggedAddresses(array $TaggedAddresses): CatalogRegistration
    {
        $this->TaggedAddresses = $TaggedAddresses;
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
     * @return CatalogRegistration
     */
    public function setNodeMeta(array $NodeMeta): CatalogRegistration
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
     * @return CatalogRegistration
     */
    public function setDatacenter(string $Datacenter): CatalogRegistration
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
     * @return CatalogRegistration
     */
    public function setService(?AgentService $Service): CatalogRegistration
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
     * @return CatalogRegistration
     */
    public function setCheck(?AgentCheck $Check): CatalogRegistration
    {
        $this->Check = $Check;
        return $this;
    }
}