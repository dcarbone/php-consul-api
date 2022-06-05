<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

/**
 * Class FakeMap
 */
class FakeMap implements \ArrayAccess, \Countable, \Iterator, \JsonSerializable
{
    /** @var array */
    private array $_map = [];

    /**
     * Map constructor.
     * @param array|null $data
     */
    public function __construct(?array $data)
    {
        if (null === $data || [] === $data) {
            return;
        }
        $this->_map = $data;
    }

    /**
     * @param mixed $input
     * @return \DCarbone\PHPConsulAPI\FakeMap|null
     */
    public static function parse($input): ?self
    {
        if (null === $input) {
            return null;
        }
        if (\is_object($input) && $input instanceof self) {
            return $input;
        }
        if (\is_array($input)) {
            return new self($input);
        }
        throw new \InvalidArgumentException(
            sprintf('Cannot parse input of type %s to %s', \gettype($input), self::class)
        );
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->_map);
    }

    public function next(): void
    {
        next($this->_map);
    }

    /**
     * @return bool|float|int|string|null
     */
    public function key()
    {
        return key($this->_map);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return null !== key($this->_map);
    }

    public function rewind(): void
    {
        reset($this->_map);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->_map[$offset]) || \array_key_exists($offset, $this->_map);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->_map[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->_map[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->_map[$offset]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->_map);
    }

    /**
     * @return object
     */
    public function jsonSerialize(): object
    {
        return (object)$this->_map;
    }
}
