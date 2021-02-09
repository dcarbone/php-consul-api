<?php declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class SessionEntry
 */
class SessionEntry extends AbstractModel
{
    private const FIELD_LOCK_DELAY     = 'LockDelay';
    private const FIELD_SERVICE_CHECKS = 'ServiceChecks';

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

    /** @var array[] */
    protected const FIELDS = [
        self::FIELD_LOCK_DELAY     => [
            Hydration::FIELD_CALLBACK => Hydration::CALLABLE_HYDRATE_DURATION,
        ],
        self::FIELD_SERVICE_CHECKS => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => ServiceCheck::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
    ];

    /**
     * SessionEntry constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        if (!($this->LockDelay instanceof Time\Duration)) {
            $this->LockDelay = Time::Duration($this->LockDelay);
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
     * @param int $createIndex
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setCreateIndex(int $createIndex): self
    {
        $this->CreateIndex = $createIndex;
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
     * @param string $id
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setID(string $id): self
    {
        $this->ID = $id;
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
     * @param string $name
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setName(string $name): self
    {
        $this->Name = $name;
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
     * @param string $node
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setNode(string $node): self
    {
        $this->Node = $node;
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
     * @param string[] $checks
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setChecks(array $checks): self
    {
        $this->Checks = [];
        foreach ($checks as $check) {
            $this->addCheck($check);
        }
        return $this;
    }

    public function addCheck(string $check): self
    {
        $this->Checks[] = $check;
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
     * @param \DCarbone\Go\Time\Duration $lockDelay
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setLockDelay(Time\Duration $lockDelay): self
    {
        $this->LockDelay = $lockDelay;
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
     * @param string $behavior
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setBehavior(string $behavior): self
    {
        $this->Behavior = $behavior;
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
     * @param string $ttl
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setTTL(string $ttl): self
    {
        $this->TTL = $ttl;
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
     * @param string $namespace
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setNamespace(string $namespace): self
    {
        $this->Namespace = $namespace;
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
     * @param string $check
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function addNodeCheck(string $check): self
    {
        $this->NodeChecks[] = $check;
        return $this;
    }

    /**
     * @param string[] $nodeChecks
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setNodeChecks(array $nodeChecks): self
    {
        $this->NodeChecks = [];
        foreach ($nodeChecks as $check) {
            $this->addNodeCheck($check);
        }
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
     * @param \DCarbone\PHPConsulAPI\Session\ServiceCheck $check
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function addServiceCheck(ServiceCheck $check): self
    {
        $this->ServiceChecks[] = $check;
        return $this;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Session\ServiceCheck[] $serviceChecks
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setServiceChecks(array $serviceChecks): self
    {
        $this->ServiceChecks = [];
        foreach ($serviceChecks as $check) {
            $this->addServiceCheck($check);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function _toAPIPayload(): array
    {
        $a = self::jsonSerialize();

        $a[self::FIELD_LOCK_DELAY] = self::durToMsec($this->LockDelay);

        return $a;
    }

    /**
     * @param \DCarbone\Go\Time\Duration|null $dur
     * @return string
     */
    private static function durToMsec(?Time\Duration $dur): string
    {
        if (null === $dur) {
            return '0ms';
        }
        $ns = $dur->Nanoseconds();
        $ms = (int)($ns / Time::Millisecond);
        if (0 < $ns && 0 === $ms) {
            return '1ms';
        }
        return \sprintf('%dms', $ms);
    }
}
