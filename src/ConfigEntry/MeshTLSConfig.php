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

class MeshTLSConfig extends AbstractType
{
    public null|MeshDirectionalTLSConfig $Incoming;
    public null|MeshDirectionalTLSConfig $Outgoing;

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        null|MeshDirectionalTLSConfig $Incoming = null,
        null|MeshDirectionalTLSConfig $Outgoing = null
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Incoming = $Incoming;
        $this->Outgoing = $Outgoing;
    }

    public function getIncoming(): null|MeshDirectionalTLSConfig
    {
        return $this->Incoming;
    }
    public function setIncoming(null|MeshDirectionalTLSConfig $Incoming): self
    {
        $this->Incoming = $Incoming;
        return $this;
    }

    public function getOutgoing(): null|MeshDirectionalTLSConfig
    {
        return $this->Outgoing;
    }

    public function setOutgoing(null|MeshDirectionalTLSConfig $Outgoing): self
    {
        $this->Outgoing = $Outgoing;
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
        foreach ((array)$decoded as $k => $V) {
            if ('Incoming' === $k) {
                $n->Incoming = null === $V ? null : MeshDirectionalTLSConfig::jsonUnserialize($V);
            } elseif ('Outgoing' === $k) {
                $n->Outgoing = null === $V ? null : MeshDirectionalTLSConfig::jsonUnserialize($V);
            } else {
                $n->{$k} = $V;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (null !== $this->Incoming) {
            $out->Incoming = $this->Incoming;
        }
        if (null !== $this->Outgoing) {
            $out->Outgoing = $this->Outgoing;
        }
        return $out;
    }
}
