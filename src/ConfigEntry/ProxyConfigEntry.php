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

class ProxyConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $Name;
    public string $Partition;
    public null|ProxyMode $Mode;
    public null|TransparentProxyConfig $TransparentProxy;
    public MutualTLSMode $MutualTLSMode;
    public null|\stdClass $Config;

    public MeshGatewayConfig $MeshGateway;
    public ExposeConfig $Expose;
    public null|AccessLogsConfig $AccessLogs;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension> */
    public array $EnvoyExtensions;
    public null|ServiceResolverFailoverPolicy $FailoverPolicy;
    public null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality;

    public function __construct(
        array|null $data = null, // Deprecated, will be removed.
        string $Kind = '',
        string $Name = '',
        string $Partition = '',
        string|ProxyMode $Mode = ProxyMode::Default,
        null|TransparentProxyConfig $TransparentProxy = null,
        string|MutualTLSMode $MutualTLSMode = MutualTLSMode::Default,
        null|\stdClass $Config = null,
        null|MeshGatewayConfig $MeshGateway = null,
        null|ExposeConfig $Expose = null,
        null|AccessLogsConfig $AccessLogs = null,
        array $EnvoyExtensions = [],
        null|ServiceResolverFailoverPolicy $FailoverPolicy = null,
        null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality = null,
        string $Namespace = '',
        null|\stdClass $Meta = null,
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
            $this->Config = $Config;
            $this->MeshGateway = $MeshGateway ?? new MeshGatewayConfig();
            $this->Expose = $Expose ?? new ExposeConfig();
            $this->AccessLogs = $AccessLogs;
            $this->EnvoyExtensions = $this->setEnvoyExtensions(...$EnvoyExtensions);
            $this->FailoverPolicy = $FailoverPolicy;
            $this->PrioritizeByLocality = $PrioritizeByLocality;
            $this->Meta = $Meta;
            $this->CreateIndex = $CreateIndex;
            $this->ModifyIndex = $ModifyIndex;

            if (null !== $data && [] !== $data) {
                self::jsonUnserialize((object)$data, $this);
            }
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

    public function getConfig(): null|\stdClass
    {
        return $this->Config;
    }

    public function setConfig(null|\stdClass $Config): self
    {
        $this->Config = $Config;
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
}
