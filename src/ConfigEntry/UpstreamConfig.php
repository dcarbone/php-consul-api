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
use function DCarbone\PHPConsulAPI\_enc_obj_if_valued;

class UpstreamConfig extends AbstractModel
{
    public string $Name;
    public string $Partition;
    public string $Namespace;
    public string $Peer;
    public string $EnvoyListenerJSON;
    public string $EnvoyClusterJSON;
    public string $Protocol;
    public int $ConnectTimeoutMs;
    public null|UpstreamLimits $Limits;
    public null|PassiveHealthCheck $PassiveHealthCheck;
    public MeshGatewayConfig $MeshGateway;

    public string $BalanceOutboundConnections;

    public function __construct(
        string $Name = '',
        string $Partition = '',
        string $Namespace = '',
        string $Peer = '',
        string $EnvoyListenerJSON = '',
        string $EnvoyClusterJSON = '',
        string $Protocol = '',
        int $ConnectTimeoutMs = 0,
        null|UpstreamLimits $Limits = null,
        null|PassiveHealthCheck $PassiveHealthCheck = null,
        null|MeshGatewayConfig $MeshGateway = null,
        string $BalanceOutboundConnections = '',
    ) {
        $this->Name = $Name;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->Peer = $Peer;
        $this->EnvoyListenerJSON = $EnvoyListenerJSON;
        $this->EnvoyClusterJSON = $EnvoyClusterJSON;
        $this->Protocol = $Protocol;
        $this->ConnectTimeoutMs = $ConnectTimeoutMs;
        $this->Limits = $Limits;
        $this->PassiveHealthCheck = $PassiveHealthCheck;
        $this->MeshGateway = $MeshGateway ?? new MeshGatewayConfig();
        $this->BalanceOutboundConnections = $BalanceOutboundConnections;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
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

    public function getPeer(): string
    {
        return $this->Peer;
    }

    public function setPeer(string $Peer): self
    {
        $this->Peer = $Peer;
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

    public function getLimits(): ?UpstreamLimits
    {
        return $this->Limits;
    }

    public function setLimits(null|UpstreamLimits $Limits): self
    {
        $this->Limits = $Limits;
        return $this;
    }

    public function getPassiveHealthCheck(): null|PassiveHealthCheck
    {
        return $this->PassiveHealthCheck;
    }

    public function setPassiveHealthCheck(null|PassiveHealthCheck $PassiveHealthCheck): self
    {
        $this->PassiveHealthCheck = $PassiveHealthCheck;
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

    public function getBalanceOutboundConnections(): string
    {
        return $this->BalanceOutboundConnections;
    }

    public function setBalanceOutboundConnections(string $BalanceOutboundConnections): self
    {
        $this->BalanceOutboundConnections = $BalanceOutboundConnections;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('envoy_listener_json' === $k) {
                $n->EnvoyListenerJSON = $v;
            } elseif ('connect_timeout_ms' === $k) {
                $n->ConnectTimeoutMs = $v;
            } elseif ('Limits' === $k) {
                $n->Limits = null === $v ? null : UpstreamLimits::jsonUnserialize($v);
            } elseif ('PassiveHealthCheck' === $k || 'passive_health_check' === $k) {
                $n->PassiveHealthCheck = null === $v ? null : PassiveHealthCheck::jsonUnserialize($v);
            } elseif ('MeshGateway' === $k) {
                $n->MeshGateway = MeshGatewayConfig::jsonUnserialize($v);
            } elseif ('balance_outbound_connections' === $k) {
                $n->BalanceOutboundConnections = $v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ('' !== $this->Name) {
            $out->Name = $this->Name;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Peer) {
            $out->Peer = $this->Peer;
        }
        if ('' !== $this->EnvoyListenerJSON) {
            $out->EnvoyListenerJSON = $this->EnvoyListenerJSON;
        }
        if ('' !== $this->EnvoyClusterJSON) {
            $out->EnvoyClusterJSON = $this->EnvoyClusterJSON;
        }
        if ('' !== $this->Protocol) {
            $out->Protocol = $this->Protocol;
        }
        if (0 !== $this->ConnectTimeoutMs) {
            $out->ConnectTimeoutMs = $this->ConnectTimeoutMs;
        }
        if (null !== $this->Limits) {
            $out->Limits = $this->Limits;
        }
        if (null !== $this->PassiveHealthCheck) {
            $out->PassiveHealthCheck = $this->PassiveHealthCheck;
        }
        _enc_obj_if_valued($out, 'MeshGateway', $this->MeshGateway);
        if ('' !== $this->BalanceOutboundConnections) {
            $out->BalanceOutboundConnections = $this->BalanceOutboundConnections;
        }
        return $out;
    }
}
