<?php declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class MeshGatewayConfig
 */
class MeshGatewayConfig extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_MESH_GATEWAY_MODE => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
    ];

    private const FIELD_MESH_GATEWAY_MODE = 'MeshGatewayMode';

    public ?string $MeshGatewayMode = null;

    /**
     * @return string|null
     */
    public function getMeshGatewayMode(): ?string
    {
        return $this->MeshGatewayMode;
    }

    /**
     * @param string|null $meshGatewayMode
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig
     */
    public function setMeshGatewayMode(?string $meshGatewayMode): self
    {
        $this->MeshGatewayMode = $meshGatewayMode;
        return $this;
    }
}
