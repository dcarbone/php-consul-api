<?php namespace DCarbone\PHPConsulAPI\Model;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class CatalogRegistration
 * @package DCarbone\PHPConsulAPI\Model
 */
class CatalogRegistration extends AbstractModel
{
    /** @var string */
    public $Node = '';
    /** @var string */
    public $Address = '';
    /** @var string */
    public $Datacenter = '';
    /** @var AgentService */
    public $Service = null;
    /** @var AgentCheck */
    public $Check = null;

    /**
     * @return string
     */
    public function getNode()
    {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return CatalogRegistration
     */
    public function setNode($Node)
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return CatalogRegistration
     */
    public function setAddress($Address)
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return $this->Datacenter;
    }

    /**
     * @param string $Datacenter
     * @return CatalogRegistration
     */
    public function setDatacenter($Datacenter)
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return AgentService
     */
    public function getService()
    {
        return $this->Service;
    }

    /**
     * @param AgentService $Service
     * @return CatalogRegistration
     */
    public function setService(AgentService $Service)
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return AgentCheck
     */
    public function getCheck()
    {
        return $this->Check;
    }

    /**
     * @param AgentCheck $Check
     * @return CatalogRegistration
     */
    public function setCheck(AgentCheck $Check)
    {
        $this->Check = $Check;
        return $this;
    }
}