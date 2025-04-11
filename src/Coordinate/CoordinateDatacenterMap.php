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
use DCarbone\PHPConsulAPI\Transcoding;

class CoordinateDatacenterMap extends AbstractModel
{
    public const FIELDS = [
        self::FIELD_COORDINATES => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => Coordinate::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
    ];

    private const FIELD_COORDINATES = 'Coordinates';

    public string $Datacenter = '';
    public string $AreaID = '';
    public array $Coordinates = [];

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function getAreaID(): string
    {
        return $this->AreaID;
    }

    public function getCoordinates(): array
    {
        return $this->Coordinates;
    }
}
