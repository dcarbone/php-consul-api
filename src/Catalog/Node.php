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
 * Class Node
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class Node extends AbstractModel
{
    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Node = '';
    /** @var string */
    public string $Address = '';
    /** @var string */
    public string $Datacenter = '';
    /** @var array */
    public array $TaggedAddresses = [];
    /** @var array */
    public array $Meta = [];
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;

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
    public function getMeta(): array
    {
        return $this->Meta;
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