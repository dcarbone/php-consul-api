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

class ServiceConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $Name;
    public string $Partition;
    public string $Protocol;
    public ProxyMode $Mode;
    public null|TransparentProxyConfig $TransparentProxy;

    public MutualTLSMode $MutualTLSMode;
    public MeshGatewayConfig $MeshGateway;
    public ExposeConfig $Expose;
    public string $ExternalSNI;
    public null|UpstreamConfiguration $UpstreamConfig;
    public null|DestinationConfig $Destination;
    public int $MaxInboundConnections;
    public int $LocalConnectTimeoutMs;
    public int $LocalRequestTimeoutMs;
    public string $BalanceInboundConnections;
    public null|RateLimits $RateLimits;
    public array $EnvoyExtensions;

    /**
     * @param array<string,mixed>|null $data
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension> $EnvoyExtensions
     */
    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $Kind = '',
        string $Name = '',
        string $Partition = '',
        string $Namespace = '',
        string $Protocol = '',
        string|ProxyMode $Mode = ProxyMode::Default,
        null|TransparentProxyConfig $TransparentProxy = null,
        string|MutualTLSMode $MutualTLSMode = MutualTLSMode::Default,
        null|MeshGatewayConfig $MeshGateway = null,
        null|ExposeConfig $Expose = null,
        string $ExternalSNI = '',
        null|UpstreamConfiguration $UpstreamConfig = null,
        null|DestinationConfig $Destination = null,
        int $MaxInboundConnections = 0,
        int $LocalConnectTimeoutMs = 0,
        int $LocalRequestTimeoutMs = 0,
        string $BalanceInboundConnections = '',
        null|RateLimits $RateLimits = null,
        array $EnvoyExtensions = [],
        null|\stdClass $Meta = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        $this->Kind = $Kind;
        $this->Name = $Name;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->Protocol = $Protocol;
        $this->Mode = is_string($Mode) ? ProxyMode::from($Mode) : $Mode;
        $this->TransparentProxy = $TransparentProxy;
        $this->MutualTLSMode = is_string($MutualTLSMode) ? MutualTLSMode::from($MutualTLSMode) : $MutualTLSMode;
        $this->MeshGateway = $MeshGateway ?? new MeshGatewayConfig();
        $this->Expose = $Expose ?? new ExposeConfig();
        $this->ExternalSNI = $ExternalSNI;
        $this->UpstreamConfig = $UpstreamConfig;
        $this->Destination = $Destination;
        $this->MaxInboundConnections = $MaxInboundConnections;
        $this->LocalConnectTimeoutMs = $LocalConnectTimeoutMs;
        $this->LocalRequestTimeoutMs = $LocalRequestTimeoutMs;
        $this->BalanceInboundConnections = $BalanceInboundConnections;
        $this->RateLimits = $RateLimits;
        $this->setEnvoyExtensions(...$EnvoyExtensions);
        $this->Meta = $Meta;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        if (null !== $data && [] !== $data) {
            self::jsonUnserialize((object)$data, $this);
        }
    }

    public function getKind(): string
    {
        return $this->Kind;
    }

    public function setKind(string $Kind): self
    {
        $this->Kind = $Kind;
        return $this;
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

    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    public function setProtocol(string $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    public function getMode(): ProxyMode
    {
        return $this->Mode;
    }

    public function setMode(ProxyMode $Mode): self
    {
        $this->Mode = $Mode;
        return $this;
    }

    public function getTransparentProxy(): null|TransparentProxyConfig
    {
        return $this->TransparentProxy;
    }

    public function setTransparentProxy(null|TransparentProxyConfig $TransparentProxy): self
    {
        $this->TransparentProxy = $TransparentProxy;
        return $this;
    }

    public function getMutualTLSMode(): MutualTLSMode
    {
        return $this->MutualTLSMode;
    }

    public function setMutualTLSMode(MutualTLSMode $MutualTLSMode): self
    {
        $this->MutualTLSMode = $MutualTLSMode;
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

    public function getExternalSNI(): string
    {
        return $this->ExternalSNI;
    }

    public function setExternalSNI(string $ExternalSNI): self
    {
        $this->ExternalSNI = $ExternalSNI;
        return $this;
    }

    public function getUpstreamConfig(): null|UpstreamConfiguration
    {
        return $this->UpstreamConfig;
    }

    public function setUpstreamConfig(null|UpstreamConfiguration $UpstreamConfig): self
    {
        $this->UpstreamConfig = $UpstreamConfig;
        return $this;
    }

    public function getDestination(): null|DestinationConfig
    {
        return $this->Destination;
    }

    public function setDestination(null|DestinationConfig $Destination): self
    {
        $this->Destination = $Destination;
        return $this;
    }

    public function getMaxInboundConnections(): int
    {
        return $this->MaxInboundConnections;
    }

    public function setMaxInboundConnections(int $MaxInboundConnections): self
    {
        $this->MaxInboundConnections = $MaxInboundConnections;
        return $this;
    }

    public function getLocalConnectTimeoutMs(): int
    {
        return $this->LocalConnectTimeoutMs;
    }

    public function setLocalConnectTimeoutMs(int $LocalConnectTimeoutMs): self
    {
        $this->LocalConnectTimeoutMs = $LocalConnectTimeoutMs;
        return $this;
    }

    public function getLocalRequestTimeoutMs(): int
    {
        return $this->LocalRequestTimeoutMs;
    }

    public function setLocalRequestTimeoutMs(int $LocalRequestTimeoutMs): self
    {
        $this->LocalRequestTimeoutMs = $LocalRequestTimeoutMs;
        return $this;
    }

    public function getBalanceInboundConnections(): string
    {
        return $this->BalanceInboundConnections;
    }

    public function setBalanceInboundConnections(string $BalanceInboundConnections): self
    {
        $this->BalanceInboundConnections = $BalanceInboundConnections;
        return $this;
    }

    public function getRateLimits(): null|RateLimits
    {
        return $this->RateLimits;
    }

    public function setRateLimits(null|RateLimits $RateLimits): self
    {
        $this->RateLimits = $RateLimits;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension>
     */
    public function getEnvoyExtensions(): array
    {
        return $this->EnvoyExtensions;
    }

    public function setEnvoyExtensions(EnvoyExtension ...$EnvoyExtensions): self
    {
        $this->EnvoyExtensions = $EnvoyExtensions;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('Mode' === $k) {
                $n->Mode = ProxyMode::from($v);
            } elseif ('TransparentProxy' === $k || 'transparent_proxy' === $k) {
                $n->TransparentProxy = null === $v ? null : TransparentProxyConfig::jsonUnserialize($v);
            } elseif ('MutualTLSMode' === $k || 'mutual_tls_mode' === $k) {
                $n->MutualTLSMode = MutualTLSMode::from($v);
            } elseif ('MeshGateway' === $k || 'mesh_gateway' === $k) {
                $n->MeshGateway = MeshGatewayConfig::jsonUnserialize($v);
            } elseif ('Expose' === $k) {
                $n->Expose = ExposeConfig::jsonUnserialize($v);
            } elseif ('external_sni' === $k) {
                $n->ExternalSNI = $v;
            } elseif ('UpstreamConfig' === $k || 'upstream_config' === $k) {
                $n->UpstreamConfig = null === $v ? null : UpstreamConfiguration::jsonUnserialize($v);
            } elseif ('Destination' === $k) {
                $n->Destination = null === $v ? null : DestinationConfig::jsonUnserialize($v);
            } elseif ('max_inbound_connections' === $k) {
                $n->MaxInboundConnections = $v;
            } elseif ('local_connect_timeout_ms' === $k) {
                $n->LocalConnectTimeoutMs = $v;
            } elseif ('local_request_timeout_ms' === $k) {
                $n->LocalRequestTimeoutMs = $v;
            } elseif ('balance_inbound_connections' === $k) {
                $n->BalanceInboundConnections = $v;
            } elseif ('RateLimits' === $k || 'rate_limits' === $k) {
                $n->RateLimits = null === $v ? null : RateLimits::jsonUnserialize($v);
            } elseif ('EnvoyExtensions' === $k || 'envoy_extensions' === $k) {
                foreach ($v as $ext) {
                    $n->EnvoyExtensions[] = EnvoyExtension::jsonUnserialize($ext);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->Kind = $this->Kind;
        $out->Name = $this->Name;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Protocol) {
            $out->Protocol = $this->Protocol;
        }
        if (ProxyMode::Default !== $this->Mode) {
            $out->Mode = $this->Mode->value;
        }
        if (null !== $this->TransparentProxy) {
            $out->TransparentProxy = $this->TransparentProxy;
        }
        if (MutualTLSMode::Default !== $this->MutualTLSMode) {
            $out->MutualTLSMode = $this->MutualTLSMode->value;
        }
        _enc_obj_if_valued($out, 'MeshGateway', $this->MeshGateway);
        _enc_obj_if_valued($out, 'Expose', $this->Expose);
        if ('' !== $this->ExternalSNI) {
            $out->ExternalSNI = $this->ExternalSNI;
        }
        if (null !== $this->UpstreamConfig) {
            $out->UpstreamConfig = $this->UpstreamConfig;
        }
        if (null !== $this->Destination) {
            $out->Destination = $this->Destination;
        }
        if (0 !== $this->MaxInboundConnections) {
            $out->MaxInboundConnections = $this->MaxInboundConnections;
        }
        if (0 !== $this->LocalConnectTimeoutMs) {
            $out->LocalConnectTimeoutMs = $this->LocalConnectTimeoutMs;
        }
        if (0 !== $this->LocalRequestTimeoutMs) {
            $out->LocalRequestTimeoutMs = $this->LocalRequestTimeoutMs;
        }
        if ('' !== $this->BalanceInboundConnections) {
            $out->BalanceInboundConnections = $this->BalanceInboundConnections;
        }
        if (null !== $this->RateLimits) {
            $out->RateLimits = $this->RateLimits;
        }
        if ([] !== $this->EnvoyExtensions) {
            $out->EnvoyExtensions = $this->EnvoyExtensions;
        }
        if (null !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
