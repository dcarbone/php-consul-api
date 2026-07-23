<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class TransparentProxyMeshConfig extends AbstractType
{
    public bool $MeshDestinationsOnly;

    /**
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        bool $MeshDestinationsOnly = false
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->MeshDestinationsOnly = $MeshDestinationsOnly;
    }

    public function isMeshDestinationsOnly(): bool
    {
        return $this->MeshDestinationsOnly;
    }

    public function setMeshDestinationsOnly(bool $MeshDestinationsOnly): self
    {
        $this->MeshDestinationsOnly = $MeshDestinationsOnly;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('mesh_destinations_only' === $k) {
                $n->MeshDestinationsOnly = $v;
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $o = new \stdClass();
        $o->MeshDestinationsOnly = $this->MeshDestinationsOnly;
        return $o;
    }
}
