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
use DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig;

class Upstream extends AbstractModel
{
    public UpstreamDestType $DestinationType;
    public string $DestinationPartition;
    public string $DestinationNamespace;
    public string $DestinationPeer;
    public string $DestinationName;
    public string $Datacenter;
    public string $LocalBindAddress;
    public int $LocalBindPort;
    public string $LocalBindSocketPath;
    public string $LocalBindSocketMode;
    /** @var array<string,mixed> */
    public null|array $Config;
    public null|MeshGatewayConfig $MeshGateway;
    public bool $CentrallyConfigured;

    /**
     * @param array<string,mixed> $Config
     */
    public function __construct(
        string|UpstreamDestType $DestinationType = UpstreamDestType::UNDEFINED,
        string $DestinationPartition = '',
        string $DestinationNamespace = '',
        string $DestinationPeer = '',
        string $DestinationName = '',
        string $Datacenter = '',
        string $LocalBindAddress = '',
        int $LocalBindPort = 0,
        string $LocalBindSocketPath = '',
        string $LocalBindSocketMode = '',
        array $Config = [],
        null|MeshGatewayConfig $MeshGateway = null,
        bool $CentrallyConfigured = false,
    ) {
        $this->setDestinationType($DestinationType);
        $this->DestinationPartition = $DestinationPartition;
        $this->DestinationNamespace = $DestinationNamespace;
        $this->DestinationPeer = $DestinationPeer;
        $this->DestinationName = $DestinationName;
        $this->Datacenter = $Datacenter;
        $this->LocalBindAddress = $LocalBindAddress;
        $this->LocalBindPort = $LocalBindPort;
        $this->LocalBindSocketPath = $LocalBindSocketPath;
        $this->LocalBindSocketMode = $LocalBindSocketMode;
        $this->setConfig($Config);
        $this->MeshGateway = $MeshGateway;
        $this->CentrallyConfigured = $CentrallyConfigured;
    }

    public function getDestinationType(): UpstreamDestType
    {
        return $this->DestinationType;
    }

    public function setDestinationType(string|UpstreamDestType $DestinationType): self
    {
        $this->DestinationType = $DestinationType instanceof UpstreamDestType ? $DestinationType : UpstreamDestType::from($DestinationType);
        return $this;
    }

    public function getDestinationPartition(): string
    {
        return $this->DestinationPartition;
    }

    public function setDestinationPartition(string $DestinationPartition): self
    {
        $this->DestinationPartition = $DestinationPartition;
        return $this;
    }

    public function getDestinationNamespace(): string
    {
        return $this->DestinationNamespace;
    }

    public function setDestinationNamespace(string $DestinationNamespace): self
    {
        $this->DestinationNamespace = $DestinationNamespace;
        return $this;
    }

    public function getDestinationPeer(): string
    {
        return $this->DestinationPeer;
    }

    public function setDestinationPeer(string $DestinationPeer): self
    {
        $this->DestinationPeer = $DestinationPeer;
        return $this;
    }

    public function getDestinationName(): string
    {
        return $this->DestinationName;
    }

    public function setDestinationName(string $DestinationName): self
    {
        $this->DestinationName = $DestinationName;
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

    public function getLocalBindAddress(): string
    {
        return $this->LocalBindAddress;
    }

    public function setLocalBindAddress(string $LocalBindAddress): self
    {
        $this->LocalBindAddress = $LocalBindAddress;
        return $this;
    }

    public function getLocalBindPort(): int
    {
        return $this->LocalBindPort;
    }

    public function setLocalBindPort(int $LocalBindPort): self
    {
        $this->LocalBindPort = $LocalBindPort;
        return $this;
    }

    public function getLocalBindSocketPath(): string
    {
        return $this->LocalBindSocketPath;
    }

    public function setLocalBindSocketPath(string $LocalBindSocketPath): self
    {
        $this->LocalBindSocketPath = $LocalBindSocketPath;
        return $this;
    }

    public function getLocalBindSocketMode(): string
    {
        return $this->LocalBindSocketMode;
    }

    public function setLocalBindSocketMode(string $LocalBindSocketMode): self
    {
        $this->LocalBindSocketMode = $LocalBindSocketMode;
        return $this;
    }

    /**
     * @return null|array<string,mixed>
     */
    public function getConfig(): null|array
    {
        return $this->Config;
    }

    /**
     * @param \stdClass|array<string,mixed>|null $Config
     * @return $this
     */
    public function setConfig(null|\stdClass|array $Config): self
    {
        if (null == $Config) {
            $this->Config = null;
            return $this;
        }
        $this->Config = [];
        foreach ($Config as $k => $v) {
            $this->Config[$k] = $v;
        }
        return $this;
    }

    public function getMeshGateway(): null|MeshGatewayConfig
    {
        return $this->MeshGateway;
    }

    public function setMeshGateway(null|MeshGatewayConfig $MeshGateway): self
    {
        $this->MeshGateway = $MeshGateway;
        return $this;
    }

    public function isCentrallyConfigured(): bool
    {
        return $this->CentrallyConfigured;
    }

    public function setCentrallyConfigured(bool $CentrallyConfigured): self
    {
        $this->CentrallyConfigured = $CentrallyConfigured;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('DestinationType' === $k) {
                $n->setDestinationType($v);
            } elseif ('MeshGateway' === $k) {
                $n->MeshGateway = MeshGatewayConfig::jsonUnserialize($v);
            } elseif ('Config' === $k) {
                $n->setConfig($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ($this->DestinationType !== UpstreamDestType::UNDEFINED) {
            $out->DestinationType = $this->DestinationType;
        }
        if ('' !== $this->DestinationPartition) {
            $out->DestinationPartition = $this->DestinationPartition;
        }
        if ('' !== $this->DestinationNamespace) {
            $out->DestinationNamespace = $this->DestinationNamespace;
        }
        if ('' !== $this->DestinationPeer) {
            $out->DestinationPeer = $this->DestinationPeer;
        }
        $out->DestinationName = $this->DestinationName;
        if ('' !== $this->Datacenter) {
            $out->Datacenter = $this->Datacenter;
        }
        if ('' !== $this->LocalBindAddress) {
            $out->LocalBindAddress = $this->LocalBindAddress;
        }
        if (0 !== $this->LocalBindPort) {
            $out->LocalBindPort = $this->LocalBindPort;
        }
        if ('' !== $this->LocalBindSocketPath) {
            $out->LocalBindSocketPath = $this->LocalBindSocketPath;
        }
        if ('' !== $this->LocalBindSocketMode) {
            $out->LocalBindSocketMode = $this->LocalBindSocketMode;
        }
        if (null !== $this->Config) {
            $out->Config = $this->Config;
        }
        if (null !== $this->MeshGateway) {
            $out->MeshGateway = $this->MeshGateway;
        }
        if ($this->CentrallyConfigured) {
            $out->CentrallyConfigured = $this->CentrallyConfigured;
        }
        return $out;
    }
}
