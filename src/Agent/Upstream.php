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
use DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class Upstream
 */
class Upstream extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_DESTINATION_TYPE      => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_DESTINATION_NAMESPACE => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_DATACENTER            => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_LOCAL_BIND_ADDRESS    => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_LOCAL_BIND_PORT       => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_CONFIG                => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::MIXED,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_MESH_GATEWAY          => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => MeshGatewayConfig::class,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
    ];

    private const FIELD_DESTINATION_TYPE      = 'DestinationType';
    private const FIELD_DESTINATION_NAMESPACE = 'DestinationNamespace';
    private const FIELD_DATACENTER            = 'Datacenter';
    private const FIELD_LOCAL_BIND_ADDRESS    = 'LocalBindAddress';
    private const FIELD_LOCAL_BIND_PORT       = 'LocalBindPort';
    private const FIELD_CONFIG                = 'Config';
    private const FIELD_MESH_GATEWAY          = 'MeshGateway';

    /** @var string */
    public string $DestinationType = '';
    /** @var string */
    public string $DestinationNamespace = '';
    /** @var string */
    public string $DestinationName = '';
    /** @var string */
    public string $Datacenter = '';
    /** @var string */
    public string $LocalBindAddress = '';
    /** @var int */
    public int $LocalBindPort = 0;
    /** @var array */
    public array $Config = [];
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig */
    public MeshGatewayConfig $MeshGatewayConfig;

    /**
     * Upstream constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->MeshGatewayConfig)) {
            $this->MeshGatewayConfig = new MeshGatewayConfig(null);
        }
    }

    /**
     * @return string
     */
    public function getDestinationType(): string
    {
        return $this->DestinationType;
    }

    /**
     * @param string $DestinationType
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setDestinationType(string $DestinationType): self
    {
        $this->DestinationType = $DestinationType;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationNamespace(): string
    {
        return $this->DestinationNamespace;
    }

    /**
     * @param string $DestinationNamespace
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setDestinationNamespace(string $DestinationNamespace): self
    {
        $this->DestinationNamespace = $DestinationNamespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationName(): string
    {
        return $this->DestinationName;
    }

    /**
     * @param string $DestinationName
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setDestinationName(string $DestinationName): self
    {
        $this->DestinationName = $DestinationName;
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
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalBindAddress(): string
    {
        return $this->LocalBindAddress;
    }

    /**
     * @param string $LocalBindAddress
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setLocalBindAddress(string $LocalBindAddress): self
    {
        $this->LocalBindAddress = $LocalBindAddress;
        return $this;
    }

    /**
     * @return int
     */
    public function getLocalBindPort(): int
    {
        return $this->LocalBindPort;
    }

    /**
     * @param int $LocalBindPort
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setLocalBindPort(int $LocalBindPort): self
    {
        $this->LocalBindPort = $LocalBindPort;
        return $this;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->Config;
    }

    /**
     * @param array $Config
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setConfig(array $Config): self
    {
        $this->Config = $Config;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig
     */
    public function getMeshGatewayConfig(): MeshGatewayConfig
    {
        return $this->MeshGatewayConfig;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig $MeshGatewayConfig
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setMeshGatewayConfig(MeshGatewayConfig $MeshGatewayConfig): self
    {
        $this->MeshGatewayConfig = $MeshGatewayConfig;
        return $this;
    }
}
