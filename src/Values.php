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
 * Class Values
 */
class Values implements \Iterator, \ArrayAccess, \Countable, \JsonSerializable
{
    /** @var array */
    private array $values = [];

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        if (isset($this->values[$key])) {
            return $this->values[$key][0];
        }

        return '';
    }

    /**
     * @param string $key
     * @return string[]
     */
    public function getAll(string $key): array
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        return [];
    }

    /**
     * @param string $key
     * @param string ...$value
     */
    public function set(string $key, string ...$value): void
    {
        $this->values[$key] = $value;
    }

    /**
     * @param string $key
     * @param string ...$value
     */
    public function add(string $key, string ...$value): void
    {
        if (isset($this->values[$key])) {
            $this->values[$key] = array_merge($this->values[$key], $value);
        } else {
            $this->values[$key] = $value;
        }
    }

    /**
     * @param string $key
     */
    public function delete(string $key): void
    {
        unset($this->values[$key]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->values);
    }

    /**
     * @return array
     */
    public function toPsr7Array(): array
    {
        return $this->values;
    }

    /**
     * @return array|string
     */
    public function current()
    {
        return current($this->values);
    }

    public function next(): void
    {
        next($this->values);
    }

    /**
     * @return string|null
     */
    public function key(): ?string
    {
        return key($this->values);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return null !== key($this->values);
    }

    public function rewind(): void
    {
        reset($this->values);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * @param string $offset
     * @return string
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param string $offset
     * @param string $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset): void
    {
        $this->delete($offset);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->values;
    }

    /**
     * @param string $v
     * @return string
     */
    protected function encode(string $v): string
    {
        return $v;
    }

    /**
     * @return string
     */
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
                    $str .= sprintf('%s=%s', $k, $this->encode($v));
                }
            }
        }
        return $str;
    }
}
