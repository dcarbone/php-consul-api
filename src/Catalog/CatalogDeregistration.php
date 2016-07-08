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

/**
 * Class CatalogDeregistration
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogDeregistration extends AbstractDefinedStrictCollection
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
            'ServiceID' => null,
            'CheckID' => null,
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
     * @return string
     */
    public function getServiceID()
    {
        return (string)$this->_storage['ServiceID'];
    }

    /**
     * @param string $serviceID
     * @return $this
     */
    public function setServiceID($serviceID)
    {
        $this->_storage['ServiceID'] = $serviceID;
        return $this;
    }

    /**
     * @return string
     */
    public function getCheckID()
    {
        return (string)$this->_storage['CheckID'];
    }

    /**
     * @param string $checkID
     * @return $this
     */
    public function setCheckID($checkID)
    {
        $this->_storage['CheckID'] = $checkID;
        return $this;
    }
}