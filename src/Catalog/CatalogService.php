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
 * Class CatalogService
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogService extends AbstractModel
{
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Node = '';
    /** @var string */
    public $Address = '';
    /** @var string */
    public $Datacenter = '';
    /** @var array */
    public $TaggedAddresses = [];
    /** @var array */
    public $NodeMeta = [];
    /** @var string */
    public $ServiceID = '';
    /** @var string */
    public $ServiceName = '';
    /** @var string */
    public $ServiceAddress = '';
    /** @var string[] */
    public $ServiceTags = [];
    /** @var int */
    public $ServicePort = 0;
    /** @var bool */
    public $ServiceEnableTagOverride = false;
    /** @var int */
    public $CreateIndex = 0;
    /** @var int */
    public $ModifyIndex = 0;

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->Node;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->Address;
    }

    /**
     * @return string
     */
    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    /**
     * @return array
     */
    public function getTaggedAddresses(): array
    {
        return $this->TaggedAddresses;
    }

    /**
     * @return array
     */
    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    /**
     * @return string
     */
    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    /**
     * @return string
     */
    public function getServiceAddress(): string
    {
        return $this->ServiceAddress;
    }

    /**
     * @return string[]
     */
    public function getServiceTags(): array
    {
        return $this->ServiceTags;
    }

    /**
     * @return int
     */
    public function getServicePort(): int
    {
        return $this->ServicePort;
    }

    /**
     * @return bool
     */
    public function isServiceEnableTagOverride(): bool
    {
        return $this->ServiceEnableTagOverride;
    }

    /**
     * @return int
     */
    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    /**
     * @return int
     */
    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }
}