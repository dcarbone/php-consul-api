<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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
 * Class KVTree
 */
class KVTree implements \RecursiveIterator, \Countable, \JsonSerializable, \ArrayAccess
{
    /** @var string */
    private string $_prefix;

    /** @var \DCarbone\PHPConsulAPI\KV\KVPair[]|\DCarbone\PHPConsulAPI\KV\KVTree[] */
    private array $_children = [];

    /**
     * KVTree constructor.
     * @param string $prefix
     */
    public function __construct(string $prefix)
    {
        $this->_prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->_prefix;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVPair|\DCarbone\PHPConsulAPI\KV\KVTree can return any type
     */
    public function current()
    {
        return current($this->_children);
    }

    public function next(): void
    {
        next($this->_children);
    }

    /**
     * @return string scalar on success, or null on failure
     */
    public function key()
    {
        return key($this->_children);
    }

    /**
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return null !== key($this->_children);
    }

    public function rewind(): void
    {
        reset($this->_children);
    }

    /**
     * @return bool true if the current entry can be iterated over, otherwise returns false
     */
    public function hasChildren()
    {
        return $this->current() instanceof self;
    }

    /**
     * @return \RecursiveIterator an iterator for the current entry
     */
    public function getChildren()
    {
        return $this->current();
    }

    /**
     * @return int the custom count as an integer
     */
    public function count()
    {
        return \count($this->_children);
    }

    /**
     * @param mixed $offset an offset to check for
     * @return bool true on success or false on failure
     */
    public function offsetExists($offset)
    {
        if (\is_string($offset)) {
            $subPath = str_replace($this->_prefix, '', $offset);
            $cnt     = substr_count($subPath, '/');

            if (1 < $cnt || (1 === $cnt && '/' !== substr($subPath, -1))) {
                $childKey = $this->_prefix . substr($subPath, 0, strpos($subPath, '/') + 1);
                if (isset($this->_children[$childKey])) {
                    return isset($this->_children[$childKey][$offset]);
                }
            }
        }

        return isset($this->_children[$offset]) || \array_key_exists($offset, $this->_children);
    }

    /**
     * @param mixed $offset the offset to retrieve
     * @return \DCarbone\PHPConsulAPI\KV\KVPair|\DCarbone\PHPConsulAPI\KV\KVTree
     */
    public function offsetGet($offset)
    {
        if (\is_string($offset)) {
            $subPath = str_replace($this->_prefix, '', $offset);
            $cnt     = substr_count($subPath, '/');
            if (1 < $cnt || (1 === $cnt && '/' !== substr($subPath, -1))) {
                $childKey = $this->_prefix . substr($subPath, 0, strpos($subPath, '/') + 1);
                if (isset($this[$childKey])) {
                    return $this->_children[$childKey][$offset];
                }
            }
        }

        if (isset($this[$offset])) {
            return $this->_children[$offset];
        }

        trigger_error(
            sprintf(
                '%s - Requested offset %s does not exist in tree with prefix "%s".',
                static::class,
                $offset,
                $this->getPrefix()
            )
        );

        return null;
    }

    /**
     * @param mixed $offset the offset to assign the value to
     * @param mixed $value the value to set
     */
    public function offsetSet($offset, $value): void
    {
        if ('string' === \gettype($offset)) {
            $subPath = str_replace($this->_prefix, '', $offset);
            $cnt     = substr_count($subPath, '/');

            if (1 < $cnt || (1 === $cnt && '/' !== substr($subPath, -1))) {
                $childKey                            = $this->_prefix . substr($subPath, 0, strpos($subPath, '/') + 1);
                $this->_children[$childKey][$offset] = $value;
            } else {
                $this->_children[$offset] = $value;
            }
        } elseif (null === $offset) {
            $this->_children[] = $value;
        } else {
            $this->_children[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset the offset to unset
     */
    public function offsetUnset($offset): void
    {
        // do nothing, yo...
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [$this->_prefix => []];
        foreach ($this->_children as $k => $child) {
            if ($child instanceof self) {
                $json[$this->_prefix] = $child;
            } elseif ($child instanceof KVPair) {
                $json[$this->_prefix][$child->Key] = $child;
            }
        }
        return $json;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->_prefix;
    }
}
