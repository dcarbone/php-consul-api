<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\AbstractModel;

class AgentServiceChecks implements \Countable, \ArrayAccess
{
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck[] */
    public array $Checks;

    public function __construct(
        iterable $Checks = [],
    ) {
        $this->setChecks(...$Checks);
    }

    public function getChecks(): array
    {
        return $this->Checks;
    }

    public function setChecks(AgentServiceCheck ...$Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }

    public function getIterator(): iterable
    {
        if ([] === $this->Checks) {
            return new \EmptyIterator();
        }
        return new \ArrayIterator($this->Checks);
    }

    public function count(): int
    {
        return count($this->Checks);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->Checks[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->Checks[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_int($offset) && $value instanceof AgentServiceCheck) {
            $this->Checks[$offset] = $value;
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Invalid offset %s or value %s, expected int and %s.',
                var_export($offset, true),
                var_export($value, true),
                AgentServiceCheck::class
            ));
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->Checks[$offset]);
    }

    public static function jsonUnserialize(array $decoded): static
    {
        $n = new static();
        foreach ($decoded as $v) {
            $n->Checks[] = AgentServiceCheck::jsonUnserialize($v);
        }
        return $n;
    }
}
