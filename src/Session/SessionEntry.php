<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Session;

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;
use function DCarbone\PHPConsulAPI\PHPLib\dur_to_millisecond;

class SessionEntry extends AbstractType
{
    public int $CreateIndex;
    public string $ID;
    public string $Name;
    public string $Node;
    public Time\Duration $LockDelay;
    public string $Behavior;
    public string $TTL;
    public string $Namespace;
    /** @var array<string> */
    public array $Checks;
    /** @var array<string> */
    public array $NodeChecks;
    /** @var array<ServiceCheck> */
    public array $ServiceChecks;

    /**
     * @param array<string> $Checks
     * @param array<string> $NodeChecks
     * @param array<ServiceCheck> $ServiceChecks
     */
    public function __construct(
        int $CreateIndex = 0,
        string $ID = '',
        string $Name = '',
        string $Node = '',
        null|string|int|float|\DateInterval|Time\Duration $LockDelay = null,
        string $Behavior = '',
        string $TTL = '',
        string $Namespace = '',
        array $Checks = [],
        array $NodeChecks = [],
        array $ServiceChecks = [],
    ) {
        $this->CreateIndex = $CreateIndex;
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Node = $Node;
        $this->LockDelay = null === $LockDelay ? new Time\Duration(0) : Time::Duration($LockDelay);
        $this->Behavior = $Behavior;
        $this->TTL = $TTL;
        $this->Namespace = $Namespace;
        $this->Checks = $Checks;
        $this->setNodeChecks(...$NodeChecks);
        $this->setServiceChecks(...$ServiceChecks);
    }

    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getNode(): string
    {
        return $this->Node;
    }

    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getLockDelay(): Time\Duration
    {
        return $this->LockDelay;
    }

    public function setLockDelay(null|string|int|float|\DateInterval|Time\Duration $LockDelay): self
    {
        $this->LockDelay = Time::Duration($LockDelay);
        return $this;
    }

    public function getBehavior(): string
    {
        return $this->Behavior;
    }

    public function setBehavior(string $Behavior): self
    {
        $this->Behavior = $Behavior;
        return $this;
    }

    public function getTTL(): string
    {
        return $this->TTL;
    }

    public function setTTL(string $TTL): self
    {
        $this->TTL = $TTL;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getChecks(): array
    {
        return $this->Checks;
    }

    public function setChecks(string ...$Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getNodeChecks(): array
    {
        return $this->NodeChecks;
    }

    public function setNodeChecks(string ...$NodeChecks): self
    {
        $this->NodeChecks = $NodeChecks;
        return $this;
    }

    /**
     * @return array<ServiceCheck>
     */
    public function getServiceChecks(): array
    {
        return $this->ServiceChecks;
    }

    public function setServiceChecks(ServiceCheck ...$ServiceChecks): self
    {
        $this->ServiceChecks = $ServiceChecks;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function _toAPIPayload(): array
    {
        $out = [];

        if ('' !== $this->Name) {
            $out['Name'] = $this->Name;
        }
        if ('' !== $this->Node) {
            $out['Node'] = $this->Node;
        }
        if (0 < $this->LockDelay->Nanoseconds()) {
            $out['LockDelay'] = dur_to_millisecond($this->LockDelay);
        }
        if ([] !== $this->Checks) {
            $out['Checks'] = $this->Checks;
        }
        if ([] !== $this->NodeChecks) {
            $out['NodeChecks'] = $this->NodeChecks;
        }
        if ([] !== $this->ServiceChecks) {
            $out['ServiceChecks'] = $this->ServiceChecks;
        }
        if ('' !== $this->Behavior) {
            $out['Behavior'] = $this->Behavior;
        }
        if ('' !== $this->TTL) {
            $out['TTL'] = $this->TTL;
        }

        return $out;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('LockDelay' === $k) {
                $n->LockDelay = Time::Duration($v);
            } elseif ('ServiceChecks' === $k) {
                $n->ServiceChecks = [];
                if (null !== $v) {
                    foreach ($v as $sc) {
                        $n->ServiceChecks[] = ServiceCheck::jsonUnserialize($sc);
                    }
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->CreateIndex = $this->CreateIndex;
        $out->ID = $this->ID;
        $out->Name = $this->Name;
        $out->Node = $this->Node;
        $out->LockDelay = (string)$this->LockDelay;
        $out->Behavior = $this->Behavior;
        $out->TTL = $this->TTL;
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        $out->Checks = $this->Checks;
        $out->NodeChecks = $this->NodeChecks;
        $out->ServiceChecks = $this->ServiceChecks;
        return $out;
    }
}
