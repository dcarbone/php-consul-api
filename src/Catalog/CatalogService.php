<?php

declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class CatalogService
 */
class CatalogService extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SERVICE_TAGGED_ADDRESSES => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ServiceAddress::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
        self::FIELD_SERVICE_WEIGHTS          => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => Weights::class,
        ],
        self::FIELD_SERVICE_PROXY            => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => AgentServiceConnectProxyConfig::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_HEALTH_CHECKS            => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => HealthChecks::class,
        ],
        self::FIELD_NAMESPACE                => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_SERVICE_TAGGED_ADDRESSES = 'ServiceTaggedAddresses';
    private const FIELD_SERVICE_WEIGHTS          = 'ServiceWeights';
    private const FIELD_SERVICE_PROXY            = 'ServiceProxy';
    private const FIELD_HEALTH_CHECKS            = 'HealthChecks';
    private const FIELD_NAMESPACE                = 'Namespace';

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
    public array $NodeMeta = [];
    /** @var string */
    public string $ServiceID = '';
    /** @var string */
    public string $ServiceName = '';
    /** @var string */
    public string $ServiceAddress = '';
    /** @var \DCarbone\PHPConsulAPI\Catalog\ServiceAddress[] */
    public array $ServiceTaggedAddresses = [];
    /** @var string[] */
    public array $ServiceTags = [];
    /** @var array */
    public array $ServiceMeta = [];
    /** @var int */
    public int $ServicePort = 0;
    /** @var \DCarbone\PHPConsulAPI\Catalog\Weights */
    public Weights $ServiceWeights;
    /** @var bool */
    public bool $ServiceEnableTagOverride = false;
    /** @var int */
    public int $CreateIndex = 0;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig|null */
    public ?AgentServiceConnectProxyConfig $ServiceProxy = null;
    /** @var int */
    public int $ModifyIndex = 0;
    /** @var string */
    public string $Namespace = '';

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
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
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
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
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
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
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return array
     */
    public function getTaggedAddresses(): array
    {
        return $this->TaggedAddresses;
    }

    /**
     * @param array $TaggedAddresses
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setTaggedAddresses(array $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    /**
     * @return array
     */
    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    /**
     * @param array $NodeMeta
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setNodeMeta(array $NodeMeta): self
    {
        $this->NodeMeta = $NodeMeta;
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
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServiceID(string $ServiceID): self
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    /**
     * @param string $ServiceName
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServiceName(string $ServiceName): self
    {
        $this->ServiceName = $ServiceName;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceAddress(): string
    {
        return $this->ServiceAddress;
    }

    /**
     * @param string $ServiceAddress
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServiceAddress(string $ServiceAddress): self
    {
        $this->ServiceAddress = $ServiceAddress;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\ServiceAddress[]
     */
    public function getServiceTaggedAddresses(): array
    {
        return $this->ServiceTaggedAddresses;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\ServiceAddress[] $ServiceTaggedAddresses
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServiceTaggedAddresses(array $ServiceTaggedAddresses): self
    {
        $this->ServiceTaggedAddresses = $ServiceTaggedAddresses;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getServiceTags(): array
    {
        return $this->ServiceTags;
    }

    /**
     * @param string[] $ServiceTags
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServiceTags(array $ServiceTags): self
    {
        $this->ServiceTags = $ServiceTags;
        return $this;
    }

    /**
     * @return array
     */
    public function getServiceMeta(): array
    {
        return $this->ServiceMeta;
    }

    /**
     * @param array $ServiceMeta
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServiceMeta(array $ServiceMeta): self
    {
        $this->ServiceMeta = $ServiceMeta;
        return $this;
    }

    /**
     * @return int
     */
    public function getServicePort(): int
    {
        return $this->ServicePort;
    }

    /**
     * @param int $ServicePort
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServicePort(int $ServicePort): self
    {
        $this->ServicePort = $ServicePort;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\Weights
     */
    public function getServiceWeights(): Weights
    {
        return $this->ServiceWeights;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\Weights $ServiceWeights
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServiceWeights(Weights $ServiceWeights): self
    {
        $this->ServiceWeights = $ServiceWeights;
        return $this;
    }

    /**
     * @return bool
     */
    public function isServiceEnableTagOverride(): bool
    {
        return $this->ServiceEnableTagOverride;
    }

    /**
     * @param bool $ServiceEnableTagOverride
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServiceEnableTagOverride(bool $ServiceEnableTagOverride): self
    {
        $this->ServiceEnableTagOverride = $ServiceEnableTagOverride;
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
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig|null
     */
    public function getServiceProxy(): ?AgentServiceConnectProxyConfig
    {
        return $this->ServiceProxy;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig|null $ServiceProxy
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setServiceProxy(?AgentServiceConnectProxyConfig $ServiceProxy): self
    {
        $this->ServiceProxy = $ServiceProxy;
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
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->Namespace;
    }

    /**
     * @param string|null $Namespace
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public function setNamespace(?string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }
}
