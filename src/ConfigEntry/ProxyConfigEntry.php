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
use DCarbone\PHPConsulAPI\Consul;
use function DCarbone\PHPConsulAPI\_enc_obj_if_valued;

class ProxyConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $Name;
    public string $Partition;
    public null|ProxyMode $Mode;
    public null|TransparentProxyConfig $TransparentProxy;
    public MutualTLSMode $MutualTLSMode;
    /** @var array<string,mixed> */
    public array $Config;

    public MeshGatewayConfig $MeshGateway;
    public ExposeConfig $Expose;
    public null|AccessLogsConfig $AccessLogs;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension> */
    public array $EnvoyExtensions;
    public null|ServiceResolverFailoverPolicy $FailoverPolicy;
    public null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality;

    /**
     * @param array<string,mixed> $Config
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension> $EnvoyExtensions
     * @param array<string,string> $Meta
     */
    public function __construct(
        string $Kind = '',
        string $Name = '',
        string $Partition = '',
        string|ProxyMode $Mode = ProxyMode::Default,
        null|TransparentProxyConfig $TransparentProxy = null,
        string|MutualTLSMode $MutualTLSMode = MutualTLSMode::Default,
        array $Config = [],
        null|MeshGatewayConfig $MeshGateway = null,
        null|ExposeConfig $Expose = null,
        null|AccessLogsConfig $AccessLogs = null,
        array $EnvoyExtensions = [],
        null|ServiceResolverFailoverPolicy $FailoverPolicy = null,
        null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality = null,
        string $Namespace = '',
        array $Meta = [],
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        {
            $this->Kind = $Kind;
            $this->Name = $Name;
            $this->Partition = $Partition;
            $this->Namespace = $Namespace;
            $this->Mode = $Mode instanceof ProxyMode ? $Mode : ProxyMode::from($Mode);
            $this->TransparentProxy = $TransparentProxy;
            $this->MutualTLSMode = $MutualTLSMode instanceof MutualTLSMode ? $MutualTLSMode : MutualTLSMode::from($MutualTLSMode);
            $this->setConfig($Config);
            $this->MeshGateway = $MeshGateway ?? new MeshGatewayConfig();
            $this->Expose = $Expose ?? new ExposeConfig();
            $this->AccessLogs = $AccessLogs;
            $this->setEnvoyExtensions(...$EnvoyExtensions);
            $this->FailoverPolicy = $FailoverPolicy;
            $this->PrioritizeByLocality = $PrioritizeByLocality;
            $this->setMeta($Meta);
            $this->CreateIndex = $CreateIndex;
            $this->ModifyIndex = $ModifyIndex;
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
        return Consul::ProxyConfigGlobal;
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

    /**
     * @return array<string,mixed>
     */
    public function getConfig(): array
    {
        return $this->Config;
    }

    /**
     * @param null|\stdClass|array<string,mixed> $Config
     * @return $this
     */
    public function setConfig(null|\stdClass|array $Config): self
    {
        $this->Config = [];
        if (null !== $Config) {
            foreach ($Config as $k => $v) {
                $this->Config[$k] = $v;
            }
        }
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

    public function getAccessLogs(): null|AccessLogsConfig
    {
        return $this->AccessLogs;
    }

    public function setAccessLogs(null|AccessLogsConfig $AccessLogs): self
    {
        $this->AccessLogs = $AccessLogs;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension>
     */
    public function getEnvoyExtensions(): array
    {
        return $this->EnvoyExtensions;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension ...$EnvoyExtensions
     */
    public function setEnvoyExtensions(EnvoyExtension ...$EnvoyExtensions): self
    {
        $this->EnvoyExtensions = $EnvoyExtensions;
        return $this;
    }

    public function getFailoverPolicy(): null|ServiceResolverFailoverPolicy
    {
        return $this->FailoverPolicy;
    }

    public function setFailoverPolicy(null|ServiceResolverFailoverPolicy $FailoverPolicy): self
    {
        $this->FailoverPolicy = $FailoverPolicy;
        return $this;
    }

    public function getPrioritizeByLocality(): null|ServiceResolverPrioritizeByLocality
    {
        return $this->PrioritizeByLocality;
    }

    public function setPrioritizeByLocality(null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality): self
    {
        $this->PrioritizeByLocality = $PrioritizeByLocality;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('ProxyMode' === $k) {
                $n->Mode = ProxyMode::from($v);
            } elseif ('TransparentProxy' === $k || 'transparent_proxy' === $k) {
                $n->TransparentProxy = TransparentProxyConfig::jsonUnserialize($v);
            } elseif ('MutualTLSMode' === $k || 'mutual_tls_mode' === $k) {
                $n->MutualTLSMode = MutualTLSMode::from($v);
            } elseif ('MeshGateway' === $k || 'mesh_gateway' === $k) {
                $n->MeshGateway = MeshGatewayConfig::jsonUnserialize($v);
            } elseif ('Expose' === $k) {
                $n->Expose = ExposeConfig::jsonUnserialize($v);
            } elseif ('AccessLogs' === $k || 'access_logs' === $k) {
                $n->AccessLogs = AccessLogsConfig::jsonUnserialize($v);
            } elseif ('EnvoyExtensions' === $k || 'envoy_extensions' === $k) {
                foreach ($v as $ext) {
                    $n->EnvoyExtensions[] = EnvoyExtension::jsonUnserialize($ext);
                }
            } elseif ('FailoverPolicy' === $k || 'failover_policy' === $k) {
                $n->FailoverPolicy = ServiceResolverFailoverPolicy::jsonUnserialize($v);
            } elseif ('PrioritizeByLocality' === $k || 'prioritize_by_locality' === $k) {
                $n->PrioritizeByLocality = ServiceResolverPrioritizeByLocality::jsonUnserialize($v);
            } elseif ('Config' === $k) {
                $n->setConfig($v);
            } elseif ('Meta' === $k) {
                $n->setMeta($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Kind = $this->Kind;
        $out->Name = $this->Name;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if (ProxyMode::Default !== $this->Mode) {
            $out->ProxyMode = $this->Mode->value;
        }
        if (null !== $this->TransparentProxy) {
            $out->TransparentProxy = $this->TransparentProxy;
        }
        if (MutualTLSMode::Default !== $this->MutualTLSMode) {
            $out->MutualTLSMode = $this->MutualTLSMode->value;
        }
        if ([] !== $this->Config) {
            $out->Config = $this->Config;
        }
        _enc_obj_if_valued($out, 'MeshGateway', $this->MeshGateway);
        _enc_obj_if_valued($out, 'Expose', $this->Expose);
        if (null !== $this->AccessLogs) {
            $out->AccessLogs = $this->AccessLogs;
        }        if ([] !== $this->EnvoyExtensions) {
            $out->EnvoyExtensions = $this->EnvoyExtensions;
        }
        if (null !== $this->FailoverPolicy) {
            $out->FailoverPolicy = $this->FailoverPolicy;
        }
        if (null !== $this->PrioritizeByLocality) {
            $out->PrioritizeByLocality = $this->PrioritizeByLocality;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ([] !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
