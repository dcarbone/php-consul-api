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
use DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Transcoding;

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

    public string $ID = '';
    public string $Node = '';
    public string $Address = '';
    public string $Datacenter = '';
    public array $TaggedAddresses = [];
    public array $NodeMeta = [];
    public string $ServiceID = '';
    public string $ServiceName = '';
    public string $ServiceAddress = '';
    public array $ServiceTaggedAddresses = [];
    public array $ServiceTags = [];
    public array $ServiceMeta = [];
    public int $ServicePort = 0;
    public Weights $ServiceWeights;
    public bool $ServiceEnableTagOverride = false;
    public int $CreateIndex = 0;
    public ?AgentServiceConnectProxyConfig $ServiceProxy = null;
    public int $ModifyIndex = 0;
    public string $Namespace = '';

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

    public function getTaggedAddresses(): array
    {
        return $this->TaggedAddresses;
    }

    public function setTaggedAddresses(array $TaggedAddresses): self
    {
        $this->TaggedAddresses = $TaggedAddresses;
        return $this;
    }

    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    public function setNodeMeta(array $NodeMeta): self
    {
        $this->NodeMeta = $NodeMeta;
        return $this;
    }

    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    public function setServiceID(string $ServiceID): self
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    public function setServiceName(string $ServiceName): self
    {
        $this->ServiceName = $ServiceName;
        return $this;
    }

    public function getServiceAddress(): string
    {
        return $this->ServiceAddress;
    }

    public function setServiceAddress(string $ServiceAddress): self
    {
        $this->ServiceAddress = $ServiceAddress;
        return $this;
    }

    public function getServiceTaggedAddresses(): array
    {
        return $this->ServiceTaggedAddresses;
    }

    public function setServiceTaggedAddresses(array $ServiceTaggedAddresses): self
    {
        $this->ServiceTaggedAddresses = $ServiceTaggedAddresses;
        return $this;
    }

    public function getServiceTags(): array
    {
        return $this->ServiceTags;
    }

    public function setServiceTags(array $ServiceTags): self
    {
        $this->ServiceTags = $ServiceTags;
        return $this;
    }

    public function getServiceMeta(): array
    {
        return $this->ServiceMeta;
    }

    public function setServiceMeta(array $ServiceMeta): self
    {
        $this->ServiceMeta = $ServiceMeta;
        return $this;
    }

    public function getServicePort(): int
    {
        return $this->ServicePort;
    }

    public function setServicePort(int $ServicePort): self
    {
        $this->ServicePort = $ServicePort;
        return $this;
    }

    public function getServiceWeights(): Weights
    {
        return $this->ServiceWeights;
    }

    public function setServiceWeights(Weights $ServiceWeights): self
    {
        $this->ServiceWeights = $ServiceWeights;
        return $this;
    }

    public function isServiceEnableTagOverride(): bool
    {
        return $this->ServiceEnableTagOverride;
    }

    public function setServiceEnableTagOverride(bool $ServiceEnableTagOverride): self
    {
        $this->ServiceEnableTagOverride = $ServiceEnableTagOverride;
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

    public function getServiceProxy(): ?AgentServiceConnectProxyConfig
    {
        return $this->ServiceProxy;
    }

    public function setServiceProxy(?AgentServiceConnectProxyConfig $ServiceProxy): self
    {
        $this->ServiceProxy = $ServiceProxy;
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

    public function getNamespace(): ?string
    {
        return $this->Namespace;
    }

    public function setNamespace(?string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }
}
