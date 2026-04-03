<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Txn;

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

/**
 * @implements \ArrayAccess<int, TxnOp>
 * @implements \IteratorAggregate<int, TxnOp>
 */
class TxnOps extends AbstractType implements \Countable, \ArrayAccess, \IteratorAggregate
{
    /** @var array<TxnOp> */
    private array $ops = [];

    /**
     * @param array<TxnOp> $ops
     */
    public function __construct(array $ops = [])
    {
        $this->ops = $ops;
    }

    public function count(): int
    {
        return \count($this->ops);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->ops[$offset]);
    }

    public function offsetGet(mixed $offset): TxnOp
    {
        return $this->ops[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            $this->ops[] = $value;
        } else {
            $this->ops[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->ops[$offset]);
    }

    /**
     * @return \ArrayIterator<int, TxnOp>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->ops);
    }

    /**
     * @return array<TxnOp>
     */
    public function all(): array
    {
        return $this->ops;
    }

    /**
     * @param array<\stdClass> $decoded
     */
    public static function jsonUnserialize(array $decoded): self
    {
        $n = new self();
        foreach ($decoded as $v) {
            $n->ops[] = TxnOp::jsonUnserialize($v);
        }
        return $n;
    }

    /**
     * @return array<TxnOp>
     */
    public function jsonSerialize(): array
    {
        return $this->ops;
    }
}
