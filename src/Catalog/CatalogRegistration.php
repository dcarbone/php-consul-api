<?php namespace DCarbone\PHPConsulAPI\Catalog;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
class CatalogRegistration extends AbstractModel {
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
    public function __construct(array $data = []) {
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
    public function getID(): string {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return CatalogRegistration
     */
    public function setID(string $ID): CatalogRegistration {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getNode(): string {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return CatalogRegistration
     */
    public function setNode(string $Node): CatalogRegistration {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return CatalogRegistration
     */
    public function setAddress(string $Address): CatalogRegistration {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return array
     */
    public function getTaggedAddresses(): array {
        return $this->TaggedAddresses;
    }

    /**
     * @param array $TaggedAddresses
     * @return CatalogRegistration
     */
    public function setTaggedAddresses(array $TaggedAddresses): CatalogRegistration {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    /**
     * @return array
     */
    public function getNodeMeta(): array {
        return $this->NodeMeta;
    }

    /**
     * @param array $NodeMeta
     * @return CatalogRegistration
     */
    public function setNodeMeta(array $NodeMeta): CatalogRegistration {
        $this->NodeMeta = $NodeMeta;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter(): string {
        return $this->Datacenter;
    }

    /**
     * @param string $Datacenter
     * @return CatalogRegistration
     */
    public function setDatacenter(string $Datacenter): CatalogRegistration {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function getService(): AgentService {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentService $Service
     * @return CatalogRegistration
     */
    public function setService(AgentService $Service): CatalogRegistration {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function getCheck(): AgentCheck {
        return $this->Check;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheck $Check
     * @return CatalogRegistration
     */
    public function setCheck(AgentCheck $Check): CatalogRegistration {
        $this->Check = $Check;
        return $this;
    }
}