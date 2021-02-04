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

/**
 * Class CatalogDeregistration
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogDeregistration extends AbstractModel
{
    /** @var string */
    public string $Node = '';
    /** @var string */
    public string $Address = '';
    /** @var string */
    public string $Datacenter = '';
    /** @var string */
    public string $ServiceID = '';
    /** @var string */
    public string $CheckID = '';

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return CatalogDeregistration
     */
    public function setNode(string $Node): CatalogDeregistration
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
     * @return CatalogDeregistration
     */
    public function setAddress(string $Address): CatalogDeregistration
    {
        $this->Address = $Address;
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
     * @return CatalogDeregistration
     */
    public function setDatacenter(string $Datacenter): CatalogDeregistration
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    /**
     * @param string $ServiceID
     * @return CatalogDeregistration
     */
    public function setServiceID(string $ServiceID): CatalogDeregistration
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    /**
     * @return string
     */
    public function getCheckID(): string
    {
        return $this->CheckID;
    }

    /**
     * @param string $CheckID
     * @return CatalogDeregistration
     */
    public function setCheckID(string $CheckID): CatalogDeregistration
    {
        $this->CheckID = $CheckID;
        return $this;
    }
}