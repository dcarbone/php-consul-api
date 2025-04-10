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

abstract class FakeSlice implements \Iterator, \ArrayAccess, \Countable, \JsonSerializable
{
    protected string $containedClass;

    private array $_list = [];

    private int $_size = 0;

    public function __construct(?array $children = [])
    {
        if (!isset($this->containedClass)) {
            throw new \DomainException(
                sprintf(
                    'Class "%s" must define $containedClass',
                    static::class
                )
            );
        }
        // fastpath for "empty"
        if (null === $children || [] === $children) {
            return;
        }
        foreach ($children as $child) {
            $this->append($child);
        }
    }

    public function append(array|AbstractModel|null $value): void
    {
        // validate provided value is either null or instance of allowed child class
        $value = $this->_validateValue($value);

        // set offset to current value of _size, and iterate size by 1
        $offset = $this->_size++;

        // if value is passed, clone then set.
        $this->_list[$offset] = $value;
    }

    public function current(): bool|AbstractModel
    {
        return current($this->_list);
    }

    public function next(): void
    {
        next($this->_list);
    }

    public function key(): ?int
    {
        return key($this->_list);
    }

    public function valid(): bool
    {
        return null !== key($this->_list);
    }

    public function rewind(): void
    {
        reset($this->_list);
    }

    public function offsetExists(mixed $offset): bool
    {
        return \is_int($offset) && isset($this->_list[$offset]);
    }

    public function offsetGet(mixed $offset): ?AbstractModel
    {
        $this->_validateOffset($offset);
        return $this->_list[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // if incoming offset is null, assume [] (append) operation.
        if (null === $offset) {
            $this->append($value);
            return;
        }

        // validate provided offset value
        $this->_validateOffset($offset);

        // validate value input and set
        $this->_list[$offset] = $this->_validateValue($value);
    }

    public function offsetUnset(mixed $offset): void
    {
        // validate provided offset value
        $this->_validateOffset($offset);

        // null out value in list
        $this->_list[$offset] = null;
    }

    public function count(): int
    {
        return $this->_size;
    }

    public function jsonSerialize(): array
    {
        if (0 === $this->_size) {
            return [];
        }

        $out = [];
        foreach ($this->_list as $i => $item) {
            if (null === $item) {
                $out[$i] = null;
            } else {
                $out[$i] = clone $item;
            }
        }
        return $out;
    }

    abstract protected function newChild(array $data): AbstractModel;

    private function _validateOffset(mixed $offset): void
    {
        if (!\is_int($offset)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot use offset of type "%s" with "%s"',
                    \gettype($offset),
                    static::class
                )
            );
        }
        if (0 > $offset || $offset >= $this->_size) {
            throw new \OutOfRangeException(sprintf('Offset %d does not exist in this list', $offset));
        }
    }

    private function _validateValue(mixed $value): ?AbstractModel
    {
        // fast path for null values
        if (null === $value) {
            return null;
        }

        // if instance of contained class, clone and move on
        if ($value instanceof $this->containedClass) {
            return clone $value;
        }

        // if array, construct new child
        if (\is_array($value)) {
            return $this->newChild($value);
        }

        // if we make it down here, fail.
        throw new \InvalidArgumentException(
            sprintf(
                '%s accepts only objects of type %s, null, or associative array definition as values',
                static::class,
                $this->containedClass,
            )
        );
    }
}
