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
use DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AgentServiceConnectProxyConfig
 */
class AgentServiceConnectProxyConfig extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_DESTINATION_SERVICE_NAME => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_DESTINATION_SERVICE_ID   => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_LOCAL_SERVICE_ADDRESS    => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_LOCAL_SERVICE_PORT       => [
            Hydration::FIELD_TYPE     => Hydration::INTEGER,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_CONFIG                   => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_ARRAY_TYPE => Hydration::MIXED,
            Hydration::FIELD_NULLABLE   => true,
        ],
        self::FIELD_UPSTREAMS                => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => Upstream::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
        self::FIELD_MESH_GATEWAY             => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => MeshGatewayConfig::class,
        ],
        self::FIELD_EXPOSE                   => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => ExposeConfig::class,
        ],
    ];

    private const FIELD_DESTINATION_SERVICE_NAME = 'DestinationServiceName';
    private const FIELD_DESTINATION_SERVICE_ID   = 'DestinationServiceID';
    private const FIELD_LOCAL_SERVICE_ADDRESS    = 'LocalServiceAddress';
    private const FIELD_LOCAL_SERVICE_PORT       = 'LocalServicePort';
    private const FIELD_CONFIG                   = 'Config';
    private const FIELD_UPSTREAMS                = 'Upstreams';
    private const FIELD_MESH_GATEWAY             = 'MeshGateway';
    private const FIELD_EXPOSE                   = 'Expose';

    /** @var string|null */
    public ?string $DestinationServiceName = null;
    /** @var string|null */
    public ?string $DestinationServiceID = null;
    /** @var string|null */
    public ?string $LocalServiceAddress = null;
    /** @var int|null */
    public ?int $LocalServicePort = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\Upstream[] */
    public array $Upstreams = [];
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig */
    public MeshGatewayConfig $MeshGateway;
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig */
    public ExposeConfig $Expose;

    /**
     * AgentServiceConnectProxyConfig constructor.
     * @param array|null $data
     */
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

    /**
     * @return string|null
     */
    public function getDestinationServiceName(): ?string
    {
        return $this->DestinationServiceName;
    }

    /**
     * @param string|null $DestinationServiceName
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig
     */
    public function setDestinationServiceName(?string $DestinationServiceName): self
    {
        $this->DestinationServiceName = $DestinationServiceName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDestinationServiceID(): ?string
    {
        return $this->DestinationServiceID;
    }

    /**
     * @param string|null $DestinationServiceID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig
     */
    public function setDestinationServiceID(?string $DestinationServiceID): self
    {
        $this->DestinationServiceID = $DestinationServiceID;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocalServiceAddress(): ?string
    {
        return $this->LocalServiceAddress;
    }

    /**
     * @param string|null $LocalServiceAddress
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig
     */
    public function setLocalServiceAddress(?string $LocalServiceAddress): self
    {
        $this->LocalServiceAddress = $LocalServiceAddress;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLocalServicePort(): ?int
    {
        return $this->LocalServicePort;
    }

    /**
     * @param int|null $LocalServicePort
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig
     */
    public function setLocalServicePort(?int $LocalServicePort): self
    {
        $this->LocalServicePort = $LocalServicePort;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream[]
     */
    public function getUpstreams(): array
    {
        return $this->Upstreams;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\Upstream[] $Upstreams
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig
     */
    public function setUpstreams(array $Upstreams): self
    {
        $this->Upstreams = $Upstreams;
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig
     */
    public function setExpose(ExposeConfig $Expose): self
    {
        $this->Expose = $Expose;
        return $this;
    }
}
