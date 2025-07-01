<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Coordinate;

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

class CoordinateDatacenterMap extends AbstractModel
{
    public string $Datacenter = '';
    public string $AreaID = '';
    /** @var array<\DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry> */
    public array $Coordinates;

    /**
     * @param array<string,mixed>|null $data
     * @param array<\DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry> $Coordinates
     */
    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $Datacenter = '',
        string $AreaID = '',
        array $Coordinates = [],
    ) {
        $this->Datacenter = $Datacenter;
        $this->AreaID = $AreaID;
        $this->setCoordinates(...$Coordinates);
        if (null !== $data && [] !== $data) {
            self::jsonUnserialize((object)($data), $this);
        }
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function getAreaID(): string
    {
        return $this->AreaID;
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('Coordinates' === $k) {
                $n->Coordinates = [];
                foreach ($v as $vv) {
                    $n->Coordinates[] = CoordinateEntry::jsonUnserialize($vv);
                }
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
        $out->Datacenter = $this->Datacenter;
        $out->AreaID = $this->AreaID;
        $out->Coordinates = $this->Coordinates;
        return $out;
    }
}
