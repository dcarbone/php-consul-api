<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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
use DCarbone\PHPConsulAPI\Catalog\ServiceAddress;
use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\HasStringTags;
use DCarbone\PHPConsulAPI\Transcoding;

class AgentService extends AbstractModel
{
    use HasStringTags;

    protected const FIELDS = [
        self::FIELD_KIND             => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_META             => Transcoding::MAP_FIELD,
        self::FIELD_TAGGED_ADDRESSES => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ServiceAddress::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_WEIGHTS          => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => AgentWeights::class,
        ],
        self::FIELD_CREATE_INDEX     => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_MODIFY_INDEX     => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_CONTENT_HASH     => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_PROXY            => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => AgentServiceConnectProxyConfig::class,
            Transcoding::FIELD_NULLABLE  => true,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_CONNECT          => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => AgentServiceConnect::class,
            Transcoding::FIELD_NULLABLE  => true,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_NAMESPACE        => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_DATACENTER       => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_KIND             = 'Kind';
    private const FIELD_META             = 'Meta';
    private const FIELD_TAGGED_ADDRESSES = 'TaggedAddresses';
    private const FIELD_WEIGHTS          = 'Weights';
    private const FIELD_CREATE_INDEX     = 'CreateIndex';
    private const FIELD_MODIFY_INDEX     = 'ModifyIndex';
    private const FIELD_CONTENT_HASH     = 'ContentHash';
    private const FIELD_PROXY            = 'Proxy';
    private const FIELD_CONNECT          = 'Connect';
    private const FIELD_NAMESPACE        = 'Namespace';
    private const FIELD_DATACENTER       = 'Datacenter';

    public string $Kind = '';
    public string $ID = '';
    public string $Service = '';
    public FakeMap $Meta;
    public int $Port = 0;
    public string $Address = '';
    public array $TaggedAddresses = [];
    public AgentWeights $Weights;
    public bool $EnableTagOverride = false;
    public int $CreateIndex = 0;
    public int $ModifyIndex = 0;
    public string $ContentHash = '';
    public ?AgentServiceConnectProxyConfig $Proxy = null;
    public ?AgentServiceConnect $Connect = null;
    public string $Namespace = '';
    public string $Datacenter = '';

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Weights)) {
            $this->Weights = new AgentWeights(null);
        }
        if (!isset($this->Meta)) {
            $this->Meta = new FakeMap(null);
        }
    }

    public function getKind(): string
    {
        return $this->Kind;
    }

    public function setKind(string $Kind): self
    {
        $this->Kind = $Kind;
        return $this;
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

    public function getService(): string
    {
        return $this->Service;
    }

    public function setService(string $Service): self
    {
        $this->Service = $Service;
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

    public function getPort(): int
    {
        return $this->Port;
    }

    public function setPort(int $Port): self
    {
        $this->Port = $Port;
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

    public function getTaggedAddresses(): array
    {
        return $this->TaggedAddresses;
    }

    public function setTaggedAddresses(array $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    public function getWeights(): AgentWeights
    {
        return $this->Weights;
    }

    public function setWeights(AgentWeights $Weights): self
    {
        $this->Weights = $Weights;
        return $this;
    }

    public function isEnableTagOverride(): bool
    {
        return $this->EnableTagOverride;
    }

    public function setEnableTagOverride(bool $EnableTagOverride): self
    {
        $this->EnableTagOverride = $EnableTagOverride;
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

    public function getContentHash(): string
    {
        return $this->ContentHash;
    }

    public function setContentHash(string $ContentHash): self
    {
        $this->ContentHash = $ContentHash;
        return $this;
    }

    public function getProxy(): ?AgentServiceConnectProxyConfig
    {
        return $this->Proxy;
    }

    public function setProxy(?AgentServiceConnectProxyConfig $Proxy): self
    {
        $this->Proxy = $Proxy;
        return $this;
    }

    public function getConnect(): ?AgentServiceConnect
    {
        return $this->Connect;
    }

    public function setConnect(?AgentServiceConnect $Connect): self
    {
        $this->Connect = $Connect;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
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
}
