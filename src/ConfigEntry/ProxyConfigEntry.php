<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

class ProxyConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    public const FIELDS = ConfigEntry::INTERFACE_FIELDS + [
        self::FIELD_MODE              => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_CONFIG            => Transcoding::MAP_FIELD,
        self::FIELD_TRANSPARENT_PROXY => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => TransparentProxyConfig::class,
            Transcoding::FIELD_NULLABLE  => true,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_MESH_GATEWAY      => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => MeshGatewayConfig::class,
            Transcoding::FIELD_OMITEMPTY => true, // todo: does nothing as field is not nullable..
        ],
        self::FIELD_EXPOSE            => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => ExposeConfig::class,
            Transcoding::FIELD_OMITEMPTY => true, // todo: does nothing as field is not nullable,
        ],
    ];

    private const FIELD_MODE              = 'Mode';
    private const FIELD_TRANSPARENT_PROXY = 'TransparentProxy';
    private const FIELD_CONFIG            = 'Config';
    private const FIELD_MESH_GATEWAY      = 'MeshGateway';
    private const FIELD_EXPOSE            = 'Expose';

    public string $Mode = '';
    public ?TransparentProxyConfig $TransparentProxy = null;
    public ?FakeMap $Config = null;
    public MeshGatewayConfig $MeshGateway;
    public ExposeConfig $Expose;

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

    public function setConfig(mixed $Config): self
    {
        $this->Config = FakeMap::parse($Config);
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
