<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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
use DCarbone\PHPConsulAPI\Catalog\ServiceAddress;
use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\HasStringTags;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AgentService
 */
class AgentService extends AbstractModel
{
    use HasStringTags;

    protected const FIELDS = [
        self::FIELD_KIND             => Hydration::OMITEMPTY_STRING_FIELD,
        self::FIELD_META             => Hydration::MAP_FIELD,
        self::FIELD_TAGGED_ADDRESSES => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => ServiceAddress::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
            Hydration::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_WEIGHTS          => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => AgentWeights::class,
        ],
        self::FIELD_CREATE_INDEX     => Hydration::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_MODIFY_INDEX     => Hydration::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_CONTENT_HASH     => Hydration::OMITEMPTY_STRING_FIELD,
        self::FIELD_PROXY            => [
            Hydration::FIELD_TYPE      => Hydration::OBJECT,
            Hydration::FIELD_CLASS     => AgentServiceConnectProxyConfig::class,
            Hydration::FIELD_NULLABLE  => true,
            Hydration::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_CONNECT          => [
            Hydration::FIELD_TYPE      => Hydration::OBJECT,
            Hydration::FIELD_CLASS     => AgentServiceConnect::class,
            Hydration::FIELD_NULLABLE  => true,
            Hydration::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_NAMESPACE        => Hydration::OMITEMPTY_STRING_FIELD,
        self::FIELD_DATACENTER       => Hydration::OMITEMPTY_STRING_FIELD,
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

    /** @var string */
    public string $Kind = '';
    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Service = '';
    /** @var \DCarbone\PHPConsulAPI\FakeMap */
    public FakeMap $Meta;
    /** @var int */
    public int $Port = 0;
    /** @var string */
    public string $Address = '';
    /** @var \DCarbone\PHPConsulAPI\Catalog\ServiceAddress[] */
    public array $TaggedAddresses = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentWeights */
    public AgentWeights $Weights;
    /** @var bool */
    public bool $EnableTagOverride = false;
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;
    /** @var string */
    public string $ContentHash = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig|null */
    public ?AgentServiceConnectProxyConfig $Proxy = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceConnect|null */
    public ?AgentServiceConnect $Connect = null;
    /** @var string */
    public string $Namespace = '';
    /** @var string */
    public string $Datacenter = '';

    /**
     * AgentService constructor.
     * @param array|null $data
     */
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

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->Kind;
    }

    /**
     * @param string $Kind
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setKind(string $Kind): self
    {
        $this->Kind = $Kind;
        return $this;
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->Service;
    }

    /**
     * @param string $Service
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setService(string $Service): self
    {
        $this->Service = $Service;
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setMeta(FakeMap $Meta): self
    {
        $this->Meta = $Meta;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->Port;
    }

    /**
     * @param int $Port
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setPort(int $Port): self
    {
        $this->Port = $Port;
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\ServiceAddress[]
     */
    public function getTaggedAddresses(): array
    {
        return $this->TaggedAddresses;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\ServiceAddress[] $TaggedAddresses
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setTaggedAddresses(array $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentWeights
     */
    public function getWeights(): AgentWeights
    {
        return $this->Weights;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentWeights $Weights
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setWeights(AgentWeights $Weights): self
    {
        $this->Weights = $Weights;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableTagOverride(): bool
    {
        return $this->EnableTagOverride;
    }

    /**
     * @param bool $EnableTagOverride
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setEnableTagOverride(bool $EnableTagOverride): self
    {
        $this->EnableTagOverride = $EnableTagOverride;
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentHash(): string
    {
        return $this->ContentHash;
    }

    /**
     * @param string $ContentHash
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setContentHash(string $ContentHash): self
    {
        $this->ContentHash = $ContentHash;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig|null
     */
    public function getProxy(): ?AgentServiceConnectProxyConfig
    {
        return $this->Proxy;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig|null $Proxy
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setProxy(?AgentServiceConnectProxyConfig $Proxy): self
    {
        $this->Proxy = $Proxy;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnect|null
     */
    public function getConnect(): ?AgentServiceConnect
    {
        return $this->Connect;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceConnect|null $Connect
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setConnect(?AgentServiceConnect $Connect): self
    {
        $this->Connect = $Connect;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $Namespace
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }
}
