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
     * @param string $node
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration
     */
    public function setNode(string $node): self
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
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration
     */
    public function setAddress(string $address): self
    {
        $this->Address = $address;
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
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration
     */
    public function setDatacenter(string $datacenter): self
    {
        $this->Datacenter = $datacenter;
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
     * @param string $serviceID
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration
     */
    public function setServiceID(string $serviceID): self
    {
        $this->ServiceID = $serviceID;
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
     * @param string $checkID
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration
     */
    public function setCheckID(string $checkID): self
    {
        $this->CheckID = $checkID;
        return $this;
    }
}
