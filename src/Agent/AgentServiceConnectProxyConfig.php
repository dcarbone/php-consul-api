<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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
use DCarbone\PHPConsulAPI\ConfigEntry\AccessLogsConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\ProxyMode;

class AgentServiceConnectProxyConfig extends AbstractModel
{
    public array $EnvoyExtensions;
    public string $DestinationServiceName;
    public string $DestinationServiceID;
    public string $LocalServiceAddress;
    public int $LocalServicePort;
    public string $LocalServiceSocketPath;
    public ProxyMode $Mode;
    public null|TransparentProxyConfig $TransparentProxy;
    public array $Config;
    /** @var \DCarbone\PHPConsulAPI\Agent\Upstream[] */
    public array $Upstreams;
    public null|MeshGatewayConfig $MeshGateway;
    public null|ExposeConfig $Expose;
    public null|AccessLogsConfig $AccessLogs;

    public function __construct(
        null|array $data = [], // Deprecated, will be removed.
        iterable $EnvoyExtensions = [],
        string $DestinationServiceName = '',
        string $DestinationServiceID = '',
        string $LocalServiceAddress = '',
        int $LocalServicePort = 0,
        string $LocalServiceSocketPath = '',
        string|ProxyMode $Mode = ProxyMode::Default,
        null|TransparentProxyConfig $TransparentProxy = null,
        null|array|\stdClass $Config = null,
        iterable $Upstreams = [],
        null|MeshGatewayConfig $MeshGateway = null,
        null|ExposeConfig $Expose = null,
        null|AccessLogsConfig $AccessLogs = null,
    ) {
        $this->setEnvoyExtensions(...$EnvoyExtensions);
        $this->DestinationServiceName = $DestinationServiceName;
        $this->DestinationServiceID = $DestinationServiceID;
        $this->LocalServiceAddress = $LocalServiceAddress;
        $this->LocalServicePort = $LocalServicePort;
        $this->Config = null === $Config ? null : (array)$Config;
        $this->LocalServiceSocketPath = $LocalServiceSocketPath;
        $this->Mode = $Mode instanceof ProxyMode ? $Mode : ProxyMode::from($Mode);
        $this->TransparentProxy = $TransparentProxy;
        $this->setUpstreams(...$Upstreams);
        $this->MeshGateway = $MeshGateway;
        $this->Expose = $Expose;
        $this->AccessLogs = $AccessLogs;
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
        }
    }

    public function getEnvoyExtensions(): array
    {
        return $this->EnvoyExtensions;
    }

    public function addEnvoyExtension(EnvoyExtension $envoyExtension): self
    {
        $this->EnvoyExtensions[] = $envoyExtension;
        return $this;
    }

    public function setEnvoyExtensions(EnvoyExtension ...$EnvoyExtensions): self
    {
        $this->EnvoyExtensions = $EnvoyExtensions;
        return $this;
    }

    public function getDestinationServiceName(): string
    {
        return $this->DestinationServiceName;
    }

    public function setDestinationServiceName(string $DestinationServiceName): self
    {
        $this->DestinationServiceName = $DestinationServiceName;
        return $this;
    }

    public function getDestinationServiceID(): string
    {
        return $this->DestinationServiceID;
    }

    public function setDestinationServiceID(string $DestinationServiceID): self
    {
        $this->DestinationServiceID = $DestinationServiceID;
        return $this;
    }

    public function getLocalServiceAddress(): string
    {
        return $this->LocalServiceAddress;
    }

    public function setLocalServiceAddress(string $LocalServiceAddress): self
    {
        $this->LocalServiceAddress = $LocalServiceAddress;
        return $this;
    }

    public function getLocalServicePort(): int
    {
        return $this->LocalServicePort;
    }

    public function setLocalServicePort(int $LocalServicePort): self
    {
        $this->LocalServicePort = $LocalServicePort;
        return $this;
    }

    public function getLocalServiceSocketPath(): string
    {
        return $this->LocalServiceSocketPath;
    }

    public function setLocalServiceSocketPath(string $LocalServiceSocketPath): self
    {
        $this->LocalServiceSocketPath = $LocalServiceSocketPath;
        return $this;
    }

    public function getMode(): ProxyMode
    {
        return $this->Mode;
    }

    public function setMode(string|ProxyMode $Mode): self
    {
        $this->Mode = $Mode instanceof ProxyMode ? $Mode : ProxyMode::from($Mode);
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

    public function getConfig(): array
    {
        return $this->Config;
    }

    public function setConfig(array|\stdClass $Config): self
    {
        $this->Config = (array)$Config;
        return $this;
    }

    public function getUpstreams(): array
    {
        return $this->Upstreams;
    }

    public function setUpstreams(Upstream ...$Upstreams): self
    {
        $this->Upstreams = $Upstreams;
        return $this;
    }

    public function getMeshGateway(): null|MeshGatewayConfig
    {
        return $this->MeshGateway;
    }

    public function setMeshGateway(null|MeshGatewayConfig $MeshGateway): self
    {
        $this->MeshGateway = $MeshGateway;
        return $this;
    }

    public function getExpose(): null|ExposeConfig
    {
        return $this->Expose;
    }

    public function setExpose(null|ExposeConfig $Expose): self
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new static();
        foreach ($decoded as $k => $v) {
            if ('EnvoyExtensions' === $k) {
                foreach ($v as $vv) {
                    $n->EnvoyExtensions[] = EnvoyExtension::jsonUnserialize($vv);
                }
            } elseif ('Mode' === $k) {
                $n->setMode($v);
            } elseif ('TransparentProxy' === $k) {
                $n->TransparentProxy = TransparentProxyConfig::jsonUnserialize($v);
            } elseif ('Config' === $k) {
                $n->Config = (array)$v;
            } elseif ('Upstreams' === $k) {
                foreach ($v as $vv) {
                    $n->Upstreams[] = Upstream::jsonUnserialize($vv);
                }
            } elseif ('MeshGateway' === $k) {
                $n->MeshGateway = MeshGatewayConfig::jsonUnserialize($v);
            } elseif ('Expose' === $k) {
                $n->Expose = ExposeConfig::jsonUnserialize($v);
            } elseif ('AccessLogs' === $k) {
                $n->AccessLogs = null === $v ? null : AccessLogsConfig::jsonUnserialize($v);
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
        if ([] !== $this->EnvoyExtensions) {
            $out->EnvoyExtensions = $this->EnvoyExtensions;
        }
        if ('' !== $this->DestinationServiceName) {
            $out->DestinationServiceName = $this->DestinationServiceName;
        }
        if ('' !== $this->DestinationServiceID) {
            $out->DestinationServiceID = $this->DestinationServiceID;
        }
        if ('' !== $this->LocalServiceAddress) {
            $out->LocalServiceAddress = $this->LocalServiceAddress;
        }
        if (0 !== $this->LocalServicePort) {
            $out->LocalServicePort = $this->LocalServicePort;
        }
        if ([] !== $this->Config) {
            $out->Config = $this->Config;
        }
        if ('' !== $this->LocalServiceSocketPath) {
            $out->LocalServiceSocketPath = $this->LocalServiceSocketPath;
        }
        if (ProxyMode::Default !== $this->Mode) {
            $out->Mode = $this->Mode->value;
        }
        if (null !== $this->TransparentProxy) {
            $out->TransparentProxy = $this->TransparentProxy;
        }
        if ([] !== $this->Upstreams) {
            $out->Upstreams = $this->Upstreams;
        }
        if (null !== $this->MeshGateway) {
            $out->MeshGateway = $this->MeshGateway;
        }
        if (null !== $this->Expose) {
            $out->Expose = $this->Expose;
        }
        if (null !== $this->AccessLogs) {
            $out->AccessLogs = $this->AccessLogs;
        }
        return $out;
    }
}
