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

class CoordinateEntry extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_COORDINATE => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => Coordinate::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
    ];

    private const FIELD_COORDINATE = 'Coord';

    public string $Node = '';
    public string $Segment = '';
    public ?Coordinate $Coord = null;

    public function getNode(): string
    {
        return $this->Node;
    }

    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getSegment(): string
    {
        return $this->Segment;
    }

    public function setSegment(string $Segment): self
    {
        $this->Segment = $Segment;
        return $this;
    }

    public function getCoord(): ?Coordinate
    {
        return $this->Coord;
    }

    public function setCoord(?Coordinate $Coord): self
    {
        $this->Coord = $Coord;
        return $this;
    }
}
