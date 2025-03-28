<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

class FakeMap extends \ArrayIterator implements \ArrayAccess, \Countable, \Iterator, \JsonSerializable
{
    private array $_map = [];

    public function __construct(?array $data)
    {
        if (null === $data || [] === $data) {
            return;
        }
        $this->_map = $data;
    }

    public static function parse(array|FakeMap|\stdClass|null $input): ?self
    {
        if (null === $input) {
            return null;
        }
        if (\is_object($input)) {
            if ($input instanceof self) {
                return $input;
            }
            return new self((array)$input);
        }
        if (\is_array($input)) {
            return new self($input);
        }
        throw new \InvalidArgumentException(
            sprintf('Cannot parse input of type %s to %s', \gettype($input), self::class)
        );
    }

    public function current(): mixed
    {
        return current($this->_map);
    }

    public function next(): void
    {
        next($this->_map);
    }

    public function key(): int|string|null
    {
        return key($this->_map);
    }

    public function valid(): bool
    {
        return null !== key($this->_map);
    }

    public function rewind(): void
    {
        reset($this->_map);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->_map[$offset]) || \array_key_exists($offset, $this->_map);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->_map[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->_map[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->_map[$offset]);
    }

    public function count(): int
    {
        return \count($this->_map);
    }

    public function jsonSerialize(): object
    {
        return (object)$this->getArrayCopy();
    }
}
