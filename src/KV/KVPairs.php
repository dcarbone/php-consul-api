<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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

/**
 * @implements \ArrayAccess<int, \DCarbone\PHPConsulAPI\KV\KVPair>
 * @implements \IteratorAggregate<int, \DCarbone\PHPConsulAPI\KV\KVPair>
 */
class KVPairs extends AbstractType implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /** @var array<\DCarbone\PHPConsulAPI\KV\KVPair> */
    protected array $KVPairs = [];

    public function __construct(KVPair ...$KVPairs)
    {
        $this->KVPairs = $KVPairs;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->KVPairs);
    }

    public function offsetExists(mixed $offset): bool
    {
        return is_int($offset) && isset($this->KVPairs[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (!isset($this[$offset])) {
            throw new \OutOfRangeException("Offset $offset does not exist");
        }
        return $this->KVPairs[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$value instanceof KVPair) {
            throw new \InvalidArgumentException(sprintf("Value must be instance of %s", KVPair::class));
        }
        if (null === $offset) {
            $this->KVPairs[] = $value;
        } elseif (!is_int($offset)) {
            throw new \InvalidArgumentException('Offset must be an integer');
        } else {
            $this->KVPairs[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->KVPairs[$offset]);
    }

    public function count(): int
    {
        return count($this->KVPairs);
    }

    public static function jsonUnserialize(array $decoded): self
    {
        $n = new self();
        foreach ($decoded as $kv) {
            $n->KVPairs[] = KVPair::jsonUnserialize($kv);
        }
        return $n;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVPair[]
     */
    public function jsonSerialize(): array
    {
        return $this->KVPairs;
    }
}
