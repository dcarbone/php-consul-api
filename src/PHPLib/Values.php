<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PHPLib;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * @implements \IteratorAggregate<string,array<string>>
 * @implements \ArrayAccess<string,array<string>>
 */
class Values implements \IteratorAggregate, \ArrayAccess, \Countable, \JsonSerializable, \Stringable
{
    /** @var array<string,array<string>> */
    protected array $values = [];

    /**
     * @param array<string,array<string>> $values
     */
    public static function fromArray(array $values): self
    {
        $out = new self();
        foreach ($values as $hdr => $vals) {
            $out->_set((string)$hdr, ...(array)$vals);
        }
        return $out;
    }

    public function get(string $key): string
    {
        return $this->_get($key);
    }

    /**
     * @return array<string>
     */
    public function getAll(string $key): array
    {
       return $this->_getAll($key);
    }

    public function set(string $key, string ...$value): void
    {
       $this->_set($key, $value);
    }

    public function add(string $key, string ...$value): void
    {
       $this->_add($key, $value);
    }

    public function delete(string $key): void
    {
        $this->_delete($key);
    }

    protected function encode(string $v): string
    {
        return $v;
    }

    protected function _get(string $key): null|string
    {
        if (isset($this->values[$key]) && count($this->values[$key]) > 0) {
            return $this->values[$key][0];
        }
        return null;
    }

    protected function _getAll(string $key): array
    {
        return $this->values[$key] ?? [];
    }

    protected function _set(string $key, string ...$value): void
    {
        $this->values[$key] = $value;
    }

    protected function _add(string $key, string ...$value): void
    {
        if (isset($this->values[$key])) {
            $this->values[$key] = array_merge($this->values[$key], $value);
        } else {
            $this->values[$key] = $value;
        }
    }

    protected function _delete(string $key): void
    {
        unset($this->values[$key]);
    }

    public function count(): int
    {
        return count($this->values);
    }

    /**
     * @return \Traversable<string,array<string>>
     */
    public function getIterator(): \Traversable
    {
        if ([] === $this->values) {
            return new \EmptyIterator();
        }
        return new \ArrayIterator($this->values);
    }


    public function offsetExists(mixed $offset): bool
    {
        return null !== $this->_get((string)$offset);
    }

    /**
     * @return array<string>
     */
    public function offsetGet(mixed $offset): array
    {
        return $this->_getAll((string)$offset);
    }

    /**
     * @param string $offset
     * @param array<string> $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->_set((string)$offset, ...(array)$value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->_delete((string)$offset);
    }

    /**
     * @return array<string,array<string>>
     */
    public function jsonSerialize(): array
    {
        return $this->values;
    }

    public function __toString(): string
    {
        $str = '';
        foreach ($this->values as $k => $vs) {
            foreach ($vs as $v) {
                if ('' !== $str) {
                    $str .= '&';
                }
                if ('' === $v) {
                    $str .= $k;
                } else {
                    $str .= "{$k}={$this->encode($v)}";
                }
            }
        }
        return $str;
    }
}
