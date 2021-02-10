<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class Node
 */
class Node extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_TAGGED_ADDRESSES => Hydration::MAP_FIELD,
        self::FIELD_META             => Hydration::MAP_FIELD,
    ];

    private const FIELD_TAGGED_ADDRESSES = 'TaggedAddresses';
    private const FIELD_META             = 'Meta';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Node = '';
    /** @var string */
    public string $Address = '';
    /** @var string */
    public string $Datacenter = '';
    /** @var \DCarbone\PHPConsulAPI\FakeMap */
    public FakeMap $TaggedAddresses;
    /** @var \DCarbone\PHPConsulAPI\FakeMap */
    public FakeMap $Meta;
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;

    /**
     * Node constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Meta)) {
            $this->Meta = new FakeMap(null);
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
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\Catalog\Node
     */
    public function setID(string $ID): self
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
     * @return \DCarbone\PHPConsulAPI\Catalog\Node
     */
    public function setNode(string $Node): self
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
     * @return \DCarbone\PHPConsulAPI\Catalog\Node
     */
    public function setAddress(string $Address): self
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
     * @return \DCarbone\PHPConsulAPI\Catalog\Node
     */
    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\FakeMap
     */
    public function getTaggedAddresses(): FakeMap
    {
        return $this->TaggedAddresses;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\FakeMap $TaggedAddresses
     * @return \DCarbone\PHPConsulAPI\Catalog\Node
     */
    public function setTaggedAddresses(FakeMap $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\FakeMap
     */
    public function getMeta(): FakeMap
    {
        return $this->Meta;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\FakeMap $Meta
     * @return \DCarbone\PHPConsulAPI\Catalog\Node
     */
    public function setMeta(FakeMap $Meta): self
    {
        $this->Meta = $Meta;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    /**
     * @param int $CreateIndex
     * @return \DCarbone\PHPConsulAPI\Catalog\Node
     */
    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    /**
     * @param int $ModifyIndex
     * @return \DCarbone\PHPConsulAPI\Catalog\Node
     */
    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }
}
