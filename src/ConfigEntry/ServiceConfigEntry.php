<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ServiceConfigEntry
 */
class ServiceConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    protected const FIELDS = ConfigEntry::INTERFACE_FIELDS + [
        self::FIELD_PROTOCOL          => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_MODE              => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_TRANSPARENT_PROXY => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => TransparentProxyConfig::class,
            Transcoding::FIELD_NULLABLE  => true,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_MESH_GATEWAY      => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => MeshGatewayConfig::class,
            Transcoding::FIELD_OMITEMPTY => true, // todo: does nothing as it isn't nullable...
        ],
        self::FIELD_EXPOSE            => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => ExposeConfig::class,
            Transcoding::FIELD_OMITEMPTY => true, // todo: does nothing as isn't nullable..
        ],
        self::FIELD_EXTERNAL_SNI      => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_UPSTREAM_CONFIG   => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => UpstreamConfiguration::class,
            Transcoding::FIELD_NULLABLE  => true,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
    ];

    private const FIELD_PROTOCOL          = 'Protocol';
    private const FIELD_MODE              = 'Mode';
    private const FIELD_TRANSPARENT_PROXY = 'TransparentProxy';
    private const FIELD_MESH_GATEWAY      = 'MeshGateway';
    private const FIELD_EXPOSE            = 'Expose';
    private const FIELD_EXTERNAL_SNI      = 'ExternalSNI';
    private const FIELD_UPSTREAM_CONFIG   = 'UpstreamConfig';

    /** @var string */
    public string $Protocol = '';
    /** @var string */
    public string $Mode = '';
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyConfig|null */
    public ?TransparentProxyConfig $TransparentProxy = null;
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig */
    public MeshGatewayConfig $MeshGateway;
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig */
    public ExposeConfig $Expose;
    /** @var string */
    public string $ExternalSNI = '';
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfiguration|null */
    public ?UpstreamConfiguration $UpstreamConfig = null;
    /** @var \DCarbone\PHPConsulAPI\FakeMap|null */

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    /**
     * @param string $Protocol
     * @return ServiceConfigEntry
     */
    public function setProtocol(string $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->Mode;
    }

    /**
     * @param string $Mode
     * @return ServiceConfigEntry
     */
    public function setMode(string $Mode): self
    {
        $this->Mode = $Mode;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyConfig|null
     */
    public function getTransparentProxy(): ?TransparentProxyConfig
    {
        return $this->TransparentProxy;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyConfig|null $TransparentProxy
     * @return ServiceConfigEntry
     */
    public function setTransparentProxy(?TransparentProxyConfig $TransparentProxy): self
    {
        $this->TransparentProxy = $TransparentProxy;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig
     */
    public function getMeshGateway(): MeshGatewayConfig
    {
        return $this->MeshGateway;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig $MeshGateway
     * @return ServiceConfigEntry
     */
    public function setMeshGateway(MeshGatewayConfig $MeshGateway): self
    {
        $this->MeshGateway = $MeshGateway;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig
     */
    public function getExpose(): ExposeConfig
    {
        return $this->Expose;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig $Expose
     * @return ServiceConfigEntry
     */
    public function setExpose(ExposeConfig $Expose): self
    {
        $this->Expose = $Expose;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalSNI(): string
    {
        return $this->ExternalSNI;
    }

    /**
     * @param string $ExternalSNI
     * @return ServiceConfigEntry
     */
    public function setExternalSNI(string $ExternalSNI): self
    {
        $this->ExternalSNI = $ExternalSNI;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfiguration|null
     */
    public function getUpstreamConfig(): ?UpstreamConfiguration
    {
        return $this->UpstreamConfig;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfiguration|null $UpstreamConfig
     * @return ServiceConfigEntry
     */
    public function setUpstreamConfig(?UpstreamConfiguration $UpstreamConfig): self
    {
        $this->UpstreamConfig = $UpstreamConfig;
        return $this;
    }
}
