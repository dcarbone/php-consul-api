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

class MeshConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Partition;
    public TransparentProxyMeshConfig $TransparentProxy;
    public bool $AllowEnablingPermissiveMutualTLS;
    public null|MeshTLSConfig $TLS;
    public null|MeshHTTPConfig $HTTP;
    public null|PeeringMeshConfig $Peering;

    /**
     * @param \stdClass|array<string,string>|null $Meta
     */
    public function __construct(
        string $Partition = '',
        string $Namespace = '',
        null|TransparentProxyMeshConfig $TransparentProxy = null,
        bool $AllowEnablingPermissiveMutualTLS = false,
        null|MeshTLSConfig $TLS = null,
        null|MeshHTTPConfig $HTTP = null,
        null|PeeringMeshConfig $Peering = null,
        null|\stdClass|array $Meta = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0
    ) {
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->setMeta($Meta);
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->TransparentProxy = $TransparentProxy;
        $this->AllowEnablingPermissiveMutualTLS = $AllowEnablingPermissiveMutualTLS;
        $this->TLS = $TLS;
        $this->HTTP = $HTTP;
        $this->Peering = $Peering;
}

    public function getKind(): string
    {
        return Consul::MeshConfig;
    }

    public function getName(): string
    {
        return Consul::MeshConfigMesh;
    }

    public function getTransparentProxy(): TransparentProxyMeshConfig
    {
        return $this->TransparentProxy;
    }

    public function setTransparentProxy(TransparentProxyMeshConfig $TransparentProxy): self
    {
        $this->TransparentProxy = $TransparentProxy;
        return $this;
    }

    public function isAllowEnablingPermissiveMutualTLS(): bool
    {
        return $this->AllowEnablingPermissiveMutualTLS;
    }

    public function setAllowEnablingPermissiveMutualTLS(bool $AllowEnablingPermissiveMutualTLS): self
    {
        $this->AllowEnablingPermissiveMutualTLS = $AllowEnablingPermissiveMutualTLS;
        return $this;
    }

    public function getTLS(): null|MeshTLSConfig
    {
        return $this->TLS;
    }

    public function setTLS(null|MeshTLSConfig $TLS): self
    {
        $this->TLS = $TLS;
        return $this;
    }

    public function getHTTP(): null|MeshHTTPConfig
    {
        return $this->HTTP;
    }

    public function setHTTP(null|MeshHTTPConfig $HTTP): self
    {
        $this->HTTP = $HTTP;
        return $this;
    }

    public function getPeering(): null|PeeringMeshConfig
    {
        return $this->Peering;
    }

    public function setPeering(null|PeeringMeshConfig $Peering): self
    {
        $this->Peering = $Peering;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $n = null): self
    {
        $n = $n ?? new self();
        foreach ($decoded as $k => $v) {
            if ('TransparentProxy' === $k || 'transparent_proxy' === $k) {
                $n->TransparentProxy = null === $v ? new TransparentProxyMeshConfig() : TransparentProxyMeshConfig::jsonUnserialize($v);
            } elseif ('TLS' === $k) {
                $n->TLS = null === $v ? null : MeshTLSConfig::jsonUnserialize($v);
            } elseif ('HTTP' === $k) {
                $n->HTTP = null === $v ? null : MeshHTTPConfig::jsonUnserialize($v);
            } elseif ('Peering' === $k) {
                $n->Peering = null === $v ? null : PeeringMeshConfig::jsonUnserialize($v);
            } elseif ('allow_enabling_permissive_mutual_tls' === $k) {
                $n->AllowEnablingPermissiveMutualTLS = $v;
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
        $out->Kind = Consul::MeshConfigMesh;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        $out->TransparentProxy = $this->TransparentProxy;
        if ($this->AllowEnablingPermissiveMutualTLS) {
            $out->AllowEnablingPermissiveMutualTLS = true;
        }
        if (null !== $this->TLS) {
            $out->TLS = $this->TLS;
        }
        if (null !== $this->HTTP) {
            $out->HTTP = $this->HTTP;
        }
        if (null !== $this->Peering) {
            $out->Peering = $this->Peering;
        }
        if (isset($this->Meta)) {
            $out->Meta = $this->Meta;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
