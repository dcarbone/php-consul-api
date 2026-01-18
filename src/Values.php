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

/**
 * @implements \IteratorAggregate<string,array<string>>
 * @implements \ArrayAccess<string,array<string>>
 */
class Values implements \IteratorAggregate, \ArrayAccess, \Countable, \JsonSerializable
{
    /** @var array<string,array<string>> */
    private array $values = [];

    /**
     * @param array<string,array<string>> $values
     * @return self
     */
    public static function fromArray(array $values): self
    {
        $out = new self();
        foreach ($values as $hdr => $vals) {
            if (is_array($vals)) {
                $out->add($hdr, ...$vals);
            } else {
                $out->add($hdr, $vals);
            }
        }
        return $out;
    }

    public function get(string $key): string
    {
        if (isset($this->values[$key])) {
            return $this->values[$key][0];
        }

        return '';
    }

    /**
     * @param string $key
     * @return array<string>
     */
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
        return count($this->values);
    }

    /**
     * @return array<string,array<string>>
     */
    public function toPsr7Array(): array
    {
        return $this->values;
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
        return array_key_exists($offset, $this->values);
    }

    /**
     * @param mixed $offset
     * @return array<string>
     */
    public function offsetGet(mixed $offset): array
    {
        return $this->values[$offset] ?? [];
    }

    /**
     * @param string $offset
     * @param array<string> $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_array($value)) {
            $this->set($offset, ...$value);
        } else {
            $this->set($offset, $value);
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->delete($offset);
    }

    /**
     * @return array<string,array<string>>
     */
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
                    $str .= "{$k}={$this->encode($v)}";
                }
            }
        }
        return $str;
    }
}
