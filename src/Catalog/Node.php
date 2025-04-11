<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Transcoding;

class Node extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_TAGGED_ADDRESSES => Transcoding::MAP_FIELD,
        self::FIELD_META             => Transcoding::MAP_FIELD,
    ];

    private const FIELD_TAGGED_ADDRESSES = 'TaggedAddresses';
    private const FIELD_META             = 'Meta';

    public string $ID = '';
    public string $Node = '';
    public string $Address = '';
    public string $Datacenter = '';
    public FakeMap $TaggedAddresses;
    public FakeMap $Meta;
    public int $CreateIndex = 0;
    public int $ModifyIndex = 0;

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Meta)) {
            $this->Meta = new FakeMap(null);
        }
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getNode(): string
    {
        return $this->Node;
    }

    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    public function getTaggedAddresses(): FakeMap
    {
        return $this->TaggedAddresses;
    }

    public function setTaggedAddresses(FakeMap $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    public function getMeta(): FakeMap
    {
        return $this->Meta;
    }

    public function setMeta(FakeMap $Meta): self
    {
        $this->Meta = $Meta;
        return $this;
    }

    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }
}
