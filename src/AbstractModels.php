<?php declare(strict_types=1);

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
 * Class AbstractModels
 */
abstract class AbstractModels implements \Iterator, \ArrayAccess, \Countable, \JsonSerializable
{
    /** @var string */
    protected string $containedClass;

    /** @var \DCarbone\PHPConsulAPI\AbstractModel[] */
    private array $_list = [];

    /** @var int */
    private int $_size = 0;

    /**
     * AbstractModels constructor.
     * @param array|null $children
     */
    public function __construct(?array $children = [])
    {
        if (!isset($this->containedClass)) {
            throw new \DomainException(
                \sprintf(
                    'Class "%s" must define $containedClass',
                    \get_called_class()
                )
            );
        }
        if (null === $children) {
            return;
        }
        foreach ($children as $child) {
            $this->append($child);
        }
    }

    /**
     * @param array|\DCarbone\PHPConsulAPI\AbstractModel|null $value
     */
    public function append($value): void
    {
        // validate provided value is either null or instance of allowed child class
        $value = $this->_validateValue($value);

        // set offset to current value of _size, and iterate size by 1
        $offset = $this->_size++;

        // if value is passed, clone then set.
        $this->_list[$offset] = $value;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\AbstractModel|false
     */
    public function current()
    {
        return \current($this->_list);
    }

    public function next(): void
    {
        \next($this->_list);
    }

    /**
     * @return int|null
     */
    public function key()
    {
        return \key($this->_list);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return null !== \key($this->_list);
    }

    public function rewind(): void
    {
        \reset($this->_list);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return \is_int($offset) && isset($this->_list[$offset]);
    }

    /**
     * @param mixed $offset
     * @return \DCarbone\PHPConsulAPI\AbstractModel|null
     */
    public function offsetGet($offset)
    {
        $this->_validateOffset($offset);
        if (isset($this->_list[$offset])) {
            return $this->_list[$offset];
        }

        return $this->_list[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
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

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        // validate provided offset value
        $this->_validateOffset($offset);

        // null out value in list
        $this->_list[$offset] = null;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->_size;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\AbstractModel[]
     */
    public function jsonSerialize(): array
    {
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

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\AbstractModel
     */
    abstract protected function newChild(array $data): AbstractModel;

    /**
     * @param mixed $offset
     */
    private function _validateOffset($offset): void
    {
        if (!\is_int($offset)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Cannot use offset of type "%s" with "%s"',
                    \gettype($offset),
                    \get_class($this)
                )
            );
        }
        if (0 > $offset || $offset >= $this->_size) {
            throw new \OutOfRangeException(\sprintf('Offset %d does not exist in this list', $offset));
        }
    }

    /**
     * @param mixed $value
     * @return \DCarbone\PHPConsulAPI\AbstractModel|null
     */
    private function _validateValue($value): ?AbstractModel
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
            \sprintf(
                '%s accepts only objects of type %s, null, or associative array definition as values',
                \get_class($this),
                $this->containedClass,
            )
        );
    }
}
