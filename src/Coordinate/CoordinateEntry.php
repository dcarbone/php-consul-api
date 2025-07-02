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

class CoordinateEntry extends AbstractModel
{
    public string $Node;
    public string $Segment;
    public string $Partition;
    public null|Coordinate $Coord;

    /**
     * @param array<string,mixed>|null $data
     */
    public function __construct(
        string $Node = '',
        string $Segment = '',
        string $Partition = '',
        null|Coordinate $Coord = null,
    ) {
        $this->Node = $Node;
        $this->Segment = $Segment;
        $this->Partition = $Partition;
        $this->Coord = $Coord;
        if (null !== $data && [] !== $data) {
            self::jsonUnserialize((object)($data), $this);
        }
    }

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

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public function getCoord(): null|Coordinate
    {
        return $this->Coord;
    }

    public function setCoord(null|Coordinate $Coord): self
    {
        $this->Coord = $Coord;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null | self $into = null): self
    {
        $n = new self();
        foreach($decoded as $k => $v) {
            if ('Coord' === $k) {
                $n->Coord = Coordinate::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Node = $this->Node;
        $out->Segment = $this->Segment;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        $out->Coord = $this->Coord;
        return $out;
    }
}
