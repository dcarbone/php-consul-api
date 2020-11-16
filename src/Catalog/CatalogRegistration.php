<?php namespace DCarbone\PHPConsulAPI\Catalog;

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

/**
 * Class CatalogRegistration
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogRegistration extends AbstractModel
{
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Node = '';
    /** @var string */
    public $Address = '';
    /** @var array */
    public $TaggedAddresses = [];
    /** @var array */
    public $NodeMeta = [];
    /** @var string */
    public $Datacenter = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService */
    public $Service = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentCheck */
    public $Check = null;

    /**
     * CatalogRegistration constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        if (null !== $this->Service && !($this->Service instanceof AgentService)) {
            $this->Service = new AgentService((array)$this->Service);
        }
        if (null !== $this->Check && !($this->Check instanceof AgentCheck)) {
            $this->Check = new AgentCheck((array)$this->Check);
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
     * @param string $id
     * @return CatalogRegistration
     */
    public function setID(string $id): CatalogRegistration
    {
        $this->ID = $id;
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
     * @param string $node
     * @return CatalogRegistration
     */
    public function setNode(string $node): CatalogRegistration
    {
        $this->Node = $node;
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
     * @param string $address
     * @return CatalogRegistration
     */
    public function setAddress(string $address): CatalogRegistration
    {
        $this->Address = $address;
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
     * @param array $taggedAddresses
     * @return CatalogRegistration
     */
    public function setTaggedAddresses(array $taggedAddresses): CatalogRegistration
    {
        $this->TaggedAddresses = $taggedAddresses;
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
     * @param array $nodeMeta
     * @return CatalogRegistration
     */
    public function setNodeMeta(array $nodeMeta): CatalogRegistration
    {
        $this->NodeMeta = $nodeMeta;
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
     * @param string $datacenter
     * @return CatalogRegistration
     */
    public function setDatacenter(string $datacenter): CatalogRegistration
    {
        $this->Datacenter = $datacenter;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function getService(): AgentService
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentService $service
     * @return CatalogRegistration
     */
    public function setService(AgentService $service): CatalogRegistration
    {
        $this->Service = $service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function getCheck(): AgentCheck
    {
        return $this->Check;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheck $check
     * @return CatalogRegistration
     */
    public function setCheck(AgentCheck $check): CatalogRegistration
    {
        $this->Check = $check;
        return $this;
    }
}