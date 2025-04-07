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

class Values implements \Iterator, \ArrayAccess, \Countable, \JsonSerializable
{
    private array $values = [];

    public function get(string $key): string
    {
        if (isset($this->values[$key])) {
            return $this->values[$key][0];
        }

        return '';
    }

    public function getAll(string $key): array
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        return [];
    }

    public function set(string $key, string ...$value): void
    {
        $this->values[$key] = $value;
    }

    public function add(string $key, string ...$value): void
    {
        if (isset($this->values[$key])) {
            $this->values[$key] = array_merge($this->values[$key], $value);
        } else {
            $this->values[$key] = $value;
        }
    }

    public function delete(string $key): void
    {
        unset($this->values[$key]);
    }

    public function count(): int
    {
        return \count($this->values);
    }

    public function toPsr7Array(): array
    {
        return $this->values;
    }

    public function current(): array
    {
        return current($this->values);
    }

    public function next(): void
    {
        next($this->values);
    }

    public function key(): ?string
    {
        return key($this->values);
    }

    public function valid(): bool
    {
        return null !== key($this->values);
    }

    public function rewind(): void
    {
        reset($this->values);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->values[$offset]);
    }

    public function offsetGet($offset): string
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->delete($offset);
    }

    public function jsonSerialize(): array
    {
        return $this->values;
    }

    protected function encode(string $v): string
    {
        return $v;
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
                    $str .= sprintf('%s=%s', $k, $this->encode($v));
                }
            }
        }
        return $str;
    }
}
