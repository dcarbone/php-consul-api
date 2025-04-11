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
use DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig;
use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\Transcoding;

class AgentServiceConnectProxyConfig extends AbstractModel
{
    public const FIELDS = [
        self::FIELD_ENVOY_EXTENSIONS => [
            Transcoding::FIELD_CLASS => EnvoyExtension::class,
            Transcoding::FIELD_TYPE => Transcoding::ARRAY,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_DESTINATION_SERVICE_NAME => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_DESTINATION_SERVICE_ID   => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_LOCAL_SERVICE_ADDRESS    => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_LOCAL_SERVICE_PORT       => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_CONFIG                   => Transcoding::OMITEMPTY_MAP_FIELD,
        self::FIELD_UPSTREAMS                => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => Upstream::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_MESH_GATEWAY             => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => MeshGatewayConfig::class,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_EXPOSE                   => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => ExposeConfig::class,
        ],
    ];

    private const FIELD_ENVOY_EXTENSIONS = 'EnvoyExtension';
    private const FIELD_DESTINATION_SERVICE_NAME = 'DestinationServiceName';
    private const FIELD_DESTINATION_SERVICE_ID   = 'DestinationServiceID';
    private const FIELD_LOCAL_SERVICE_ADDRESS    = 'LocalServiceAddress';
    private const FIELD_LOCAL_SERVICE_PORT       = 'LocalServicePort';
    private const FIELD_CONFIG                   = 'Config';
    private const FIELD_UPSTREAMS                = 'Upstreams';
    private const FIELD_MESH_GATEWAY             = 'MeshGateway';
    private const FIELD_EXPOSE                   = 'Expose';

    public array $EnvoyExtensions = [];
    public string $DestinationServiceName = '';
    public string $DestinationServiceID = '';
    public string $LocalServiceAddress = '';
    public int $LocalServicePort = 0;
    public ?FakeMap $Config = null;
    public string $LocalServiceSocketPath = '';
    public string $Mode = '';
    public ?TransparentProxyConfig $TransparentProxy = null;
    public array $Upstreams = [];
    public MeshGatewayConfig $MeshGateway;
    public ExposeConfig $Expose;

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->MeshGateway)) {
            $this->MeshGateway = new MeshGatewayConfig(null);
        }
        if (!isset($this->Expose)) {
            $this->Expose = new ExposeConfig(null);
        }
    }

    public function getEnvoyExtensions(): array
    {
        return $this->EnvoyExtensions;
    }

    public function addEnvoyExtension(EnvoyExtension $envoyExtension): self
    {
        $this->EnvoyExtensions[] = $envoyExtension;
        return $this;
    }

    public function setEnvoyExtensions(array $EnvoyExtensions): self
    {
        $this->EnvoyExtensions = $EnvoyExtensions;
        return $this;
    }

    public function getDestinationServiceName(): string
    {
        return $this->DestinationServiceName;
    }

    public function setDestinationServiceName(string $DestinationServiceName): self
    {
        $this->DestinationServiceName = $DestinationServiceName;
        return $this;
    }

    public function getDestinationServiceID(): string
    {
        return $this->DestinationServiceID;
    }

    public function setDestinationServiceID(string $DestinationServiceID): self
    {
        $this->DestinationServiceID = $DestinationServiceID;
        return $this;
    }

    public function getLocalServiceAddress(): string
    {
        return $this->LocalServiceAddress;
    }

    public function setLocalServiceAddress(string $LocalServiceAddress): self
    {
        $this->LocalServiceAddress = $LocalServiceAddress;
        return $this;
    }

    public function getLocalServicePort(): int
    {
        return $this->LocalServicePort;
    }

    public function setLocalServicePort(int $LocalServicePort): self
    {
        $this->LocalServicePort = $LocalServicePort;
        return $this;
    }

    public function getLocalServiceSocketPath(): string
    {
        return $this->LocalServiceSocketPath;
    }

    public function setLocalServiceSocketPath(string $LocalServiceSocketPath): self
    {
        $this->LocalServiceSocketPath = $LocalServiceSocketPath;
        return $this;
    }

    public function getMode(): string
    {
        return $this->Mode;
    }

    public function setMode(string $Mode): self
    {
        $this->Mode = $Mode;
        return $this;
    }

    public function getTransparentProxy(): ?TransparentProxyConfig
    {
        return $this->TransparentProxy;
    }

    public function setTransparentProxy(?TransparentProxyConfig $TransparentProxy): self
    {
        $this->TransparentProxy = $TransparentProxy;
        return $this;
    }

    public function getConfig(): ?FakeMap
    {
        return $this->Config;
    }

    public function setConfig(array|FakeMap|\stdClass|null $Config): self
    {
        $this->Config = FakeMap::parse($Config);
        return $this;
    }

    public function getUpstreams(): array
    {
        return $this->Upstreams;
    }

    public function setUpstreams(array $Upstreams): self
    {
        $this->Upstreams = $Upstreams;
        return $this;
    }

    public function getMeshGateway(): MeshGatewayConfig
    {
        return $this->MeshGateway;
    }

    public function setMeshGateway(MeshGatewayConfig $MeshGateway): self
    {
        $this->MeshGateway = $MeshGateway;
        return $this;
    }

    public function getExpose(): ExposeConfig
    {
        return $this->Expose;
    }

    public function setExpose(ExposeConfig $Expose): self
    {
        $this->Expose = $Expose;
        return $this;
    }
}
