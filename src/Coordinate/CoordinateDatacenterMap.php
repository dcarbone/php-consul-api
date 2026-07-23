<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Coordinate;

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

class CoordinateDatacenterMap extends AbstractType
{
    public string $Datacenter;
    public string $AreaID;
    /** @var array<\DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry> */
    public array $Coordinates;

    /**
     * @param array<\DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry> $Coordinates
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $Datacenter = '',
        string $AreaID = '',
        array $Coordinates = [],
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Datacenter = $Datacenter;
        $this->AreaID = $AreaID;
        $this->setCoordinates(...$Coordinates);
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    public function getAreaID(): string
    {
        return $this->AreaID;
    }

    public function setAreaID(string $AreaID): self
    {
        $this->AreaID = $AreaID;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry[]
     */
    public function getCoordinates(): array
    {
        return $this->Coordinates;
    }

    public function setCoordinates(CoordinateEntry ...$Coordinates): self
    {
        $this->Coordinates = $Coordinates;
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
            if ('Coordinates' === $k) {
                $n->Coordinates = [];
                foreach ($v as $vv) {
                    $n->Coordinates[] = CoordinateEntry::jsonUnserialize($vv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Datacenter = $this->Datacenter;
        $out->AreaID = $this->AreaID;
        $out->Coordinates = $this->Coordinates;
        return $out;
    }
}
