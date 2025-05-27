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
    public TransparentProxyConfig $TransparentProxy;
    public bool $AllowEnablingPermissiveMutualTLS;
    public null|MeshTLSConfig $TLS;
    public null|MeshHTTPConfig $HTTP;
    public null|PeeringMeshConfig $Peering;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $Partition = '',
        string $Namespace = '',
        null|TransparentProxyConfig $TransparentProxy = null,
        bool $AllowEnablingPermissiveMutualTLS = false,
        null|MeshTLSConfig $TLS = null,
        null|MeshHTTPConfig $HTTP = null,
        null|PeeringMeshConfig $Peering = null,
        null|\stdClass $Meta = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0
    ) {
        $this->Partition         = $Partition;
        $this->Namespace = $Namespace;
        $this->Meta = $Meta;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->TransparentProxy = $TransparentProxy;
        $this->AllowEnablingPermissiveMutualTLS = $AllowEnablingPermissiveMutualTLS;
        $this->TLS = $TLS;
        $this->HTTP = $HTTP;
        $this->Peering = $Peering;

        if (null !== $data && [] !== $data) {
            self::jsonUnserialize((object)$data, $this);
        }
    }

    public function getKind(): string
    {
        return Consul::MeshConfig;
    }

    public function getName(): string
    {
        return Consul::MeshConfigMesh;
    }

    public function getTransparentProxy(): TransparentProxyConfig
    {
        return $this->TransparentProxy;
    }

    public function setTransparentProxy(TransparentProxyConfig $TransparentProxy): self
    {
        $this->TransparentProxy = $TransparentProxy;
        return $this;
    }
}
