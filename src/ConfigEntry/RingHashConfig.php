<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class RingHashConfig extends AbstractType
{
    public int $MinimumRingSize = 0;
    public int $MaximumRingSize = 0;

    public function __construct(int $MinimumRingSize = 0, int $MaximumRingSize = 0)
    {
        $this->MinimumRingSize = $MinimumRingSize;
        $this->MaximumRingSize = $MaximumRingSize;
    }

    public function getMinimumRingSize(): int
    {
        return $this->MinimumRingSize;
    }

    public function setMinimumRingSize(int $MinimumRingSize): self
    {
        $this->MinimumRingSize = $MinimumRingSize;
        return $this;
    }

    public function getMaximumRingSize(): int
    {
        return $this->MaximumRingSize;
    }

    public function setMaximumRingSize(int $MaximumRingSize): self
    {
        $this->MaximumRingSize = $MaximumRingSize;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('minimum_ring_size' === $k) {
                $n->MinimumRingSize = $v;
            } elseif ('maximum_ring_size' === $k) {
                $n->MaximumRingSize = $v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (0 !== $this->MinimumRingSize) {
            $out->MinimumRingSize = $this->MinimumRingSize;
        }
        if (0 !== $this->MaximumRingSize) {
            $out->MaximumRingSize = $this->MaximumRingSize;
        }
        return $out;
    }
}
