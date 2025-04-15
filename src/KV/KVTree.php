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

class KVTree implements \RecursiveIterator, \Countable, \JsonSerializable, \ArrayAccess
{
    private string $_prefix;

    private array $_children = [];

    public function __construct(string $prefix)
    {
        $this->_prefix = $prefix;
    }

    public function getPrefix(): string
    {
        return $this->_prefix;
    }

    public function current(): KVTree|KVPair
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
    public function key(): string
    {
        return key($this->_children);
    }

    public function valid(): bool
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
    public function hasChildren(): bool
    {
        return $this->current() instanceof self;
    }

    public function getChildren(): \RecursiveIterator|KVTree|KVPair
    {
        return $this->current();
    }

    public function count(): int
    {
        return count($this->_children);
    }

    public function offsetExists(mixed $offset): bool
    {
        if (is_string($offset)) {
            $subPath = str_replace($this->_prefix, '', $offset);
            $cnt     = substr_count($subPath, '/');

            if (1 < $cnt || (1 === $cnt && '/' !== substr($subPath, -1))) {
                $childKey = $this->_prefix . substr($subPath, 0, strpos($subPath, '/') + 1);
                if (isset($this->_children[$childKey])) {
                    return isset($this->_children[$childKey][$offset]);
                }
            }
        }

        return isset($this->_children[$offset]) || array_key_exists($offset, $this->_children);
    }

    public function offsetGet(mixed $offset): KVTree|KVPair|null
    {
        if (is_string($offset)) {
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

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ('string' === gettype($offset)) {
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

    public function offsetUnset(mixed $offset): void
    {
        // do nothing, yo...
    }

    public function jsonSerialize(): array
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

    public function __toString()
    {
        return $this->_prefix;
    }
}
