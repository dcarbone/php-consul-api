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
use DCarbone\PHPConsulAPI\Transcoding;

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

    public string $Name;
    public string $Namespace;
    public string $EnvoyListenerJSON;
    public string $EnvoyClusterJSON;
    public string $Protocol;
    public int $ConnectTimeoutMs;
    public ?UpstreamLimits $UpstreamLimits = null;
    public ?PassiveHealthCheck $PassiveHealthCheck = null;
    public ?MeshGatewayConfig $MeshGateway = null;

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function getEnvoyListenerJSON(): string
    {
        return $this->EnvoyListenerJSON;
    }

    public function setEnvoyListenerJSON(string $EnvoyListenerJSON): self
    {
        $this->EnvoyListenerJSON = $EnvoyListenerJSON;
        return $this;
    }

    public function getEnvoyClusterJSON(): string
    {
        return $this->EnvoyClusterJSON;
    }

    public function setEnvoyClusterJSON(string $EnvoyClusterJSON): self
    {
        $this->EnvoyClusterJSON = $EnvoyClusterJSON;
        return $this;
    }

    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    public function setProtocol(string $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    public function getConnectTimeoutMs(): int
    {
        return $this->ConnectTimeoutMs;
    }

    public function setConnectTimeoutMs(int $ConnectTimeoutMs): self
    {
        $this->ConnectTimeoutMs = $ConnectTimeoutMs;
        return $this;
    }

    public function getUpstreamLimits(): ?UpstreamLimits
    {
        return $this->UpstreamLimits;
    }

    public function setUpstreamLimits(?UpstreamLimits $UpstreamLimits): self
    {
        $this->UpstreamLimits = $UpstreamLimits;
        return $this;
    }

    public function getPassiveHealthCheck(): ?PassiveHealthCheck
    {
        return $this->PassiveHealthCheck;
    }

    public function setPassiveHealthCheck(?PassiveHealthCheck $PassiveHealthCheck): self
    {
        $this->PassiveHealthCheck = $PassiveHealthCheck;
        return $this;
    }

    public function getMeshGateway(): ?MeshGatewayConfig
    {
        return $this->MeshGateway;
    }

    public function setMeshGateway(?MeshGatewayConfig $MeshGateway): self
    {
        $this->MeshGateway = $MeshGateway;
        return $this;
    }
}
