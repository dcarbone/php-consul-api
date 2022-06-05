<?php

declare(strict_types=1);

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
 * Class UpstreamConfiguration
 */
class UpstreamConfiguration extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_NAME                 => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAMESPACE            => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_ENJOY_LISTENER_JSON  => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_ENVOY_CLUSTER_JSON   => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_PROTOCOL             => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_CONNECT_TIMEOUT_MS   => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_LIMITS               => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => UpstreamLimits::class,
            Transcoding::FIELD_OMITEMPTY => true,
            Transcoding::FIELD_NULLABLE  => true,
        ],
        self::FIELD_PASSIVE_HEALTH_CHECK => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => PassiveHealthCheck::class,
            Transcoding::FIELD_OMITEMPTY => true,
            Transcoding::FIELD_NULLABLE  => true,
        ],
        self::FIELD_MESH_GATEWAY         => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => MeshGatewayConfig::class,
            Transcoding::FIELD_OMITEMPTY => true,
            Transcoding::FIELD_NULLABLE  => true,
        ],
    ];

    private const FIELD_NAME                 = 'Name';
    private const FIELD_NAMESPACE            = 'Namespace';
    private const FIELD_ENJOY_LISTENER_JSON  = 'EnvoyListenerJSON';
    private const FIELD_ENVOY_CLUSTER_JSON   = 'EnvoyClusterJSON';
    private const FIELD_PROTOCOL             = 'Protocol';
    private const FIELD_CONNECT_TIMEOUT_MS   = 'ConnectTimeoutMs';
    private const FIELD_LIMITS               = 'Limits';
    private const FIELD_PASSIVE_HEALTH_CHECK = 'PassiveHealthCheck';
    private const FIELD_MESH_GATEWAY         = 'MeshGateway';

    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Namespace = '';
    /** @var string */
    public string $EnvoyListenerJSON = '';
    /** @var string */
    public string $EnvoyClusterJSON = '';
    /** @var string */
    public string $Protocol = '';
    /** @var int */
    public int $ConnectTimeoutMs = 0;
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\UpstreamLimits|null */
    public ?UpstreamLimits $UpstreamLimits = null;
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\PassiveHealthCheck|null */
    public ?PassiveHealthCheck $PassiveHealthCheck = null;
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig|null */
    public ?MeshGatewayConfig $MeshGateway = null;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return UpstreamConfiguration
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
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
     * @return UpstreamConfiguration
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvoyListenerJSON(): string
    {
        return $this->EnvoyListenerJSON;
    }

    /**
     * @param string $EnvoyListenerJSON
     * @return UpstreamConfiguration
     */
    public function setEnvoyListenerJSON(string $EnvoyListenerJSON): self
    {
        $this->EnvoyListenerJSON = $EnvoyListenerJSON;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvoyClusterJSON(): string
    {
        return $this->EnvoyClusterJSON;
    }

    /**
     * @param string $EnvoyClusterJSON
     * @return UpstreamConfiguration
     */
    public function setEnvoyClusterJSON(string $EnvoyClusterJSON): self
    {
        $this->EnvoyClusterJSON = $EnvoyClusterJSON;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    /**
     * @param string $Protocol
     * @return UpstreamConfiguration
     */
    public function setProtocol(string $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    /**
     * @return int
     */
    public function getConnectTimeoutMs(): int
    {
        return $this->ConnectTimeoutMs;
    }

    /**
     * @param int $ConnectTimeoutMs
     * @return UpstreamConfiguration
     */
    public function setConnectTimeoutMs(int $ConnectTimeoutMs): self
    {
        $this->ConnectTimeoutMs = $ConnectTimeoutMs;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\UpstreamLimits|null
     */
    public function getUpstreamLimits(): ?UpstreamLimits
    {
        return $this->UpstreamLimits;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\UpstreamLimits|null $UpstreamLimits
     * @return UpstreamConfiguration
     */
    public function setUpstreamLimits(?UpstreamLimits $UpstreamLimits): self
    {
        $this->UpstreamLimits = $UpstreamLimits;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\PassiveHealthCheck|null
     */
    public function getPassiveHealthCheck(): ?PassiveHealthCheck
    {
        return $this->PassiveHealthCheck;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\PassiveHealthCheck|null $PassiveHealthCheck
     * @return UpstreamConfiguration
     */
    public function setPassiveHealthCheck(?PassiveHealthCheck $PassiveHealthCheck): self
    {
        $this->PassiveHealthCheck = $PassiveHealthCheck;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig|null
     */
    public function getMeshGateway(): ?MeshGatewayConfig
    {
        return $this->MeshGateway;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig|null $MeshGateway
     * @return UpstreamConfiguration
     */
    public function setMeshGateway(?MeshGatewayConfig $MeshGateway): self
    {
        $this->MeshGateway = $MeshGateway;
        return $this;
    }
}
