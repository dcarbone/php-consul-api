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

final class CoordinateUnitVectorAt implements \ArrayAccess
{
    /** @var array<float> */
    public array $vec;
    public float $mag;

    /**
     * @param array<float> $vec
     */
    public function __construct(array $vec, float $mag)
    {
        $this->vec = $vec;
        $this->mag = $mag;
    }

    public function offsetExists(mixed $offset): bool
    {
        return 0 === $offset || 1 === $offset;
    }

    /**
     * @return array<float>|float
     */
    public function offsetGet(mixed $offset): array|float
    {
        return match ($offset) {
            0 => $this->vec,
            1 => $this->mag,
            default => throw new \OutOfBoundsException("Invalid offset: $offset"),
        };
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \BadMethodCallException('CoordinateUnitVectorAt cannot be mutated as if it were an array.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \BadMethodCallException('CoordinateUnitVectorAt cannot be mutated as if it were an array.');
    }
}
