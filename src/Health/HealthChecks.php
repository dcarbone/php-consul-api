<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Health;

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
use DCarbone\PHPConsulAPI\Consul;

/**
 * @implements \ArrayAccess<int, HealthCheck>
 * @implements \IteratorAggregate<int, HealthCheck>
 */
class HealthChecks extends AbstractModel implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /** @var \DCarbone\PHPConsulAPI\Health\HealthCheck[] */
    protected array $Checks = [];

    public function __construct(HealthCheck ...$Checks)
    {
        $this->Checks = $Checks;
    }

    public function AggregatedStatus(): string
    {
        $passing = $warning = $critical = $maintenance = false;
        foreach ($this->Checks as $check) {
            if (Consul::NodeMaint === $check->CheckID || str_starts_with($check->CheckID, Consul::ServiceMaintPrefix)) {
                // TODO: Maybe just return maintenance right now...?
                $maintenance = true;
                continue;
            }
            switch ($check->Status) {
                case Consul::HealthPassing:
                    $passing = true;
                    break;
                case Consul::HealthWarning:
                    $warning = true;
                    break;
                case Consul::HealthCritical:
                    $critical = true;
                    break;

                default:
                    return '';
            }
        }

        if ($maintenance) {
            return Consul::HealthMaint;
        }
        if ($critical) {
            return Consul::HealthCritical;
        }
        if ($warning) {
            return Consul::HealthWarning;
        }
        if ($passing) {
            return Consul::HealthPassing;
        }
        return Consul::HealthPassing;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->Checks);
    }

    public function count(): int
    {
        return count($this->Checks);
    }

    public function offsetExists($offset): bool
    {
        return is_int($offset) && isset($this->Checks[$offset]);
    }

    public function offsetGet($offset): null|HealthCheck
    {
        if (!isset($this->Checks[$offset])) {
            throw new \OutOfRangeException("Offset $offset does not exist");
        }
        return $this->Checks[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof HealthCheck) {
            throw new \InvalidArgumentException(sprintf("Value must be an instance of %s", HealthCheck::class));
        }
        if (null === $offset) {
            $this->Checks[] = $value;
        } else {
            if (!is_int($offset)) {
                throw new \InvalidArgumentException('Offset must be an integer');
            }
            $this->Checks[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->Checks[$offset]);
    }

    /**
     * @param array<\stdClass> $decoded
     */
    public static function jsonUnserialize(array $decoded, null|self $into = null): static
    {
        $n = $into ?? new self();
        foreach ($decoded as $d) {
            $n->Checks[] = HealthCheck::jsonUnserialize($d);
        }
        return $n;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthCheck[]
     */
    public function jsonSerialize(): array
    {
        return $this->Checks;
    }
}
