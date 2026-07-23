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

class ServiceRoute extends AbstractType
{
    public null|ServiceRouteMatch $Match;
    public null|ServiceRouteDestination $Destination;

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        null|ServiceRouteMatch $Match = null,
        null|ServiceRouteDestination $Destination = null,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Match = $Match;
        $this->Destination = $Destination;
    }

    public function getMatch(): null|ServiceRouteMatch
    {
        return $this->Match;
    }

    public function setMatch(null|ServiceRouteMatch $Match): self
    {
        $this->Match = $Match;
        return $this;
    }

    public function getDestination(): null|ServiceRouteDestination
    {
        return $this->Destination;
    }

    public function setDestination(null|ServiceRouteDestination $Destination): self
    {
        $this->Destination = $Destination;
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
            if ('Match' === $k) {
                $n->Match = null === $v ? null : ServiceRouteMatch::jsonUnserialize($v);
            } elseif ('Destination' === $k) {
                $n->Destination = null === $v ? null : ServiceRouteDestination::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (null !== $this->Match) {
            $out->Match = $this->Match;
        }
        if (null !== $this->Destination) {
            $out->Destination = $this->Destination;
        }
        return $out;
    }
}
