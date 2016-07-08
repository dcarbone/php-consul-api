<?php namespace DCarbone\PHPConsulAPI\Catalog;

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

use DCarbone\PHPConsulAPI\AbstractDefinedStrictCollection;
use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentService;

/**
 * Class CatalogRegistration
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogRegistration extends AbstractDefinedStrictCollection
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Node' => null,
            'Address' => null,
            'Datacenter' => null,
            'Service' => null,
            'Check' => null,
        );
    }

    /**
     * @return string
     */
    public function getNode()
    {
        return (string)$this->_storage['Node'];
    }

    /**
     * @param string $node
     * @return $this
     */
    public function setNode($node)
    {
        $this->_storage['Node'] = $node;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return (string)$this->_storage['Address'];
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->_storage['Address'] = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return (string)$this->_storage['Datacenter'];
    }

    /**
     * @param string $datacenter
     * @return $this
     */
    public function setDatacenter($datacenter)
    {
        $this->_storage['Datacenter'] = $datacenter;
        return $this;
    }

    /**
     * @return AgentService|null
     */
    public function getService()
    {
        return $this->_storage['Service'];
    }

    /**
     * @param AgentService $service
     * @return $this
     */
    public function setService(AgentService $service)
    {
        $this->_storage['Service'] = $service;
        return $this;
    }

    /**
     * @return AgentCheck|null
     */
    public function getCheck()
    {
        return $this->_storage['Check'];
    }

    /**
     * @param AgentCheck $check
     * @return $this
     */
    public function setCheck(AgentCheck $check)
    {
        $this->_storage['Check'] = $check;
        return $this;
    }
}