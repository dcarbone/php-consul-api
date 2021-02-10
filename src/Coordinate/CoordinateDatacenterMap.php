<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Coordinate;

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
 * Class CoordinateDatacenterMap
 */
class CoordinateDatacenterMap extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_COORDINATES => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => Coordinate::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
    ];

    private const FIELD_COORDINATES = 'Coordinates';

    /** @var string */
    public string $Datacenter = '';
    /** @var string */
    public string $AreaID = '';
    /** @var \DCarbone\PHPConsulAPI\Coordinate\Coordinate[] */
    public array $Coordinates = [];

    /**
     * @return string
     */
    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    /**
     * @return string
     */
    public function getAreaID(): string
    {
        return $this->AreaID;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Coordinate\Coordinate[]
     */
    public function getCoordinates(): array
    {
        return $this->Coordinates;
    }
}
