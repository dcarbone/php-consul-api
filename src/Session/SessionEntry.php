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
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

use function DCarbone\PHPConsulAPI\dur_to_millisecond;

class SessionEntry extends AbstractModel
{
    public const FIELDS = [
        self::FIELD_LOCK_DELAY     => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_DURATION,
        ],
        self::FIELD_SERVICE_CHECKS => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ServiceCheck::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
        self::FIELD_NAMESPACE      => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_NAME           = 'Name';
    private const FIELD_NODE           = 'Node';
    private const FIELD_LOCK_DELAY     = 'LockDelay';
    private const FIELD_CHECKS         = 'Checks';
    private const FIELD_NODE_CHECKS    = 'NodeChecks';
    private const FIELD_SERVICE_CHECKS = 'ServiceChecks';
    private const FIELD_BEHAVIOR       = 'Behavior';
    private const FIELD_TTL            = 'TTL';
    private const FIELD_NAMESPACE      = 'Namespace';

    public int $CreateIndex = 0;
    public string $ID = '';
    public string $Name = '';
    public string $Node = '';
    public Time\Duration $LockDelay;
    public string $Behavior = '';
    public string $TTL = '';
    public string $Namespace = '';
    public array $Checks = [];
    public array $NodeChecks = [];
    public array $ServiceChecks = [];

    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!isset($this->LockDelay)) {
            $this->LockDelay = new Time\Duration(0);
        }
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

    public function setLockDelay(Time\Duration $LockDelay): self
    {
        $this->LockDelay = $LockDelay;
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

    public function getChecks(): array
    {
        return $this->Checks;
    }

    public function setChecks(array $Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }

    public function getNodeChecks(): array
    {
        return $this->NodeChecks;
    }

    public function setNodeChecks(array $NodeChecks): self
    {
        $this->NodeChecks = $NodeChecks;
        return $this;
    }

    public function getServiceChecks(): array
    {
        return $this->ServiceChecks;
    }

    public function setServiceChecks(array $ServiceChecks): self
    {
        $this->ServiceChecks = $ServiceChecks;
        return $this;
    }

    public function _toAPIPayload(): array
    {
        $out = [];

        if ('' !== $this->Name) {
            $out[self::FIELD_NAME] = $this->Name;
        }
        if ('' !== $this->Node) {
            $out[self::FIELD_NODE] = $this->Node;
        }
        if (0 < $this->LockDelay->Nanoseconds()) {
            $out[self::FIELD_LOCK_DELAY] = dur_to_millisecond($this->LockDelay);
        }
        if ([] !== $this->Checks) {
            $out[self::FIELD_CHECKS] = $this->Checks;
        }
        if ([] !== $this->NodeChecks) {
            $out[self::FIELD_NODE_CHECKS] = $this->NodeChecks;
        }
        if ([] !== $this->ServiceChecks) {
            $out[self::FIELD_SERVICE_CHECKS] = $this->ServiceChecks;
        }
        if ('' !== $this->Behavior) {
            $out[self::FIELD_BEHAVIOR] = $this->Behavior;
        }
        if ('' !== $this->TTL) {
            $out[self::FIELD_TTL] = $this->TTL;
        }

        return $out;
    }
}
