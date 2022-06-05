<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Session;

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

use function DCarbone\PHPConsulAPI\dur_to_millisecond;

/**
 * Class SessionEntry
 */
class SessionEntry extends AbstractModel
{
    protected const FIELDS = [
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

    /** @var int */
    public int $CreateIndex = 0;
    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Node = '';
    /** @var \DCarbone\Go\Time\Duration */
    public Time\Duration $LockDelay;
    /** @var string */
    public string $Behavior = '';
    /** @var string */
    public string $TTL = '';
    /** @var string */
    public string $Namespace = '';
    /** @var string[] */
    public array $Checks = [];
    /** @var string[] */
    public array $NodeChecks = [];
    /** @var \DCarbone\PHPConsulAPI\Session\ServiceCheck[] */
    public array $ServiceChecks = [];

    /**
     * SessionEntry constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!isset($this->LockDelay)) {
            $this->LockDelay = new Time\Duration(0);
        }
    }

    /**
     * @return int
     */
    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    /**
     * @param int $CreateIndex
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getLockDelay(): Time\Duration
    {
        return $this->LockDelay;
    }

    /**
     * @param \DCarbone\Go\Time\Duration $LockDelay
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setLockDelay(Time\Duration $LockDelay): self
    {
        $this->LockDelay = $LockDelay;
        return $this;
    }

    /**
     * @return string
     */
    public function getBehavior(): string
    {
        return $this->Behavior;
    }

    /**
     * @param string $Behavior
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setBehavior(string $Behavior): self
    {
        $this->Behavior = $Behavior;
        return $this;
    }

    /**
     * @return string
     */
    public function getTTL(): string
    {
        return $this->TTL;
    }

    /**
     * @param string $TTL
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setTTL(string $TTL): self
    {
        $this->TTL = $TTL;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $Namespace
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getChecks(): array
    {
        return $this->Checks;
    }

    /**
     * @param string[] $Checks
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setChecks(array $Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getNodeChecks(): array
    {
        return $this->NodeChecks;
    }

    /**
     * @param string[] $NodeChecks
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setNodeChecks(array $NodeChecks): self
    {
        $this->NodeChecks = $NodeChecks;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Session\ServiceCheck[]
     */
    public function getServiceChecks(): array
    {
        return $this->ServiceChecks;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Session\ServiceCheck[] $ServiceChecks
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setServiceChecks(array $ServiceChecks): self
    {
        $this->ServiceChecks = $ServiceChecks;
        return $this;
    }

    /**
     * @return array
     */
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
