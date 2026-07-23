<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Txn;

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

/**
 * @implements \ArrayAccess<int, TxnResult>
 * @implements \IteratorAggregate<int, TxnResult>
 */
class TxnResults extends AbstractType implements \Countable, \ArrayAccess, \IteratorAggregate
{
    /** @var array<TxnResult> */
    private array $results = [];

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     * @param array<TxnResult> $results
     */
    public function __construct(null|array $data = null, array $results = [])
    {
        if (null !== $data) {
            if ([] === $data || $data[array_key_first($data)] instanceof TxnResult) {
                /** @var array<TxnResult> $data */
                $this->results = $data;
                return;
            }
            self::_hydrateFromDecoded($data, $this);
            return;
        }
        $this->results = $results;
    }

    public function count(): int
    {
        return \count($this->results);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->results[$offset]);
    }

    public function offsetGet(mixed $offset): TxnResult
    {
        return $this->results[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            $this->results[] = $value;
        } else {
            $this->results[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->results[$offset]);
    }

    /**
     * @return \ArrayIterator<int, TxnResult>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->results);
    }

    /**
     * @return array<TxnResult>
     */
    public function all(): array
    {
        return $this->results;
    }

    /**
     * @param array<\stdClass> $decoded
     */
    public static function jsonUnserialize(array $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    /**
     * @param array<\stdClass> $decoded
     */
    protected static function _hydrateFromDecoded(array $decoded, self $n): void
    {
        foreach ($decoded as $v) {
            $n->results[] = TxnResult::jsonUnserialize($v);
        }
    }

    /**
     * @return array<TxnResult>
     */
    public function jsonSerialize(): array
    {
        return $this->results;
    }
}
