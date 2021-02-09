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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class Upstream
 */
class Upstream extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_DESTINATION_TYPE      => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_DESTINATION_NAMESPACE => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_DATACENTER            => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_LOCAL_BIND_ADDRESS    => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_LOCAL_BIND_PORT       => [
            Hydration::FIELD_TYPE     => Hydration::INTEGER,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_CONFIG                => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_ARRAY_TYPE => Hydration::MIXED,
            Hydration::FIELD_NULLABLE   => true,
        ],
        self::FIELD_MESH_GATEWAY          => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => MeshGatewayConfig::class,
        ],
    ];

    private const FIELD_DESTINATION_TYPE      = 'DestinationType';
    private const FIELD_DESTINATION_NAMESPACE = 'DestinationNamespace';
    private const FIELD_DATACENTER            = 'Datacenter';
    private const FIELD_LOCAL_BIND_ADDRESS    = 'LocalBindAddress';
    private const FIELD_LOCAL_BIND_PORT       = 'LocalBindPort';
    private const FIELD_CONFIG                = 'Config';
    private const FIELD_MESH_GATEWAY          = 'MeshGateway';

    /** @var string|null */
    public ?string $DestinationType = null;
    /** @var string|null */
    public ?string $DestinationNamespace = null;
    /** @var string */
    public string $DestinationName = '';
    /** @var string|null */
    public ?string $Datacenter = null;
    /** @var string|null */
    public ?string $LocalBindAddress = null;
    /** @var int|null */
    public ?int $LocalBindPort = null;
    /** @var array */
    public array $Config = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\MeshGatewayConfig */
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
     * @return string|null
     */
    public function getDestinationType(): ?string
    {
        return $this->DestinationType;
    }

    /**
     * @param string|null $destinationType
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setDestinationType(?string $destinationType): self
    {
        $this->DestinationType = $destinationType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDestinationNamespace(): ?string
    {
        return $this->DestinationNamespace;
    }

    /**
     * @param string|null $destinationNamespace
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setDestinationNamespace(?string $destinationNamespace): self
    {
        $this->DestinationNamespace = $destinationNamespace;
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
     * @param string $destinationName
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setDestinationName(string $destinationName): self
    {
        $this->DestinationName = $destinationName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDatacenter(): ?string
    {
        return $this->Datacenter;
    }

    /**
     * @param string|null $datacenter
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setDatacenter(?string $datacenter): self
    {
        $this->Datacenter = $datacenter;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocalBindAddress(): ?string
    {
        return $this->LocalBindAddress;
    }

    /**
     * @param string|null $localBindAddress
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setLocalBindAddress(?string $localBindAddress): self
    {
        $this->LocalBindAddress = $localBindAddress;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLocalBindPort(): ?int
    {
        return $this->LocalBindPort;
    }

    /**
     * @param int|null $localBindPort
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setLocalBindPort(?int $localBindPort): self
    {
        $this->LocalBindPort = $localBindPort;
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
     * @param array $config
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setConfig(array $config): self
    {
        $this->Config = $config;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\MeshGatewayConfig
     */
    public function getMeshGatewayConfig(): MeshGatewayConfig
    {
        return $this->MeshGatewayConfig;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\MeshGatewayConfig $meshGatewayConfig
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream
     */
    public function setMeshGatewayConfig(MeshGatewayConfig $meshGatewayConfig): self
    {
        $this->MeshGatewayConfig = $meshGatewayConfig;
        return $this;
    }
}
