<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Session;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class SessionEntry
 * @package DCarbone\PHPConsulAPI\Session
 */
class SessionEntry extends AbstractModel
{
    /** @var int */
    public $CreateIndex = 0;
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $Node = '';
    /** @var \DCarbone\Go\Time\Duration */
    public $LockDelay = null;
    /** @var string */
    public $Behavior = '';
    /** @var string */
    public $TTL = '';
    /** @var string */
    public $Namespace = '';
    /** @var string[] */
    public $Checks = [];
    /** @var string[] */
    public $NodeChecks = [];
    /** @var \DCarbone\PHPConsulAPI\Session\ServiceCheck[] */
    public $ServiceChecks = [];

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
        foreach ($this->ServiceChecks as &$check) {
            if (!($check instanceof ServiceCheck)) {
                $check = new ServiceCheck((array)$check);
            }
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
    public function setCreateIndex(int $createIndex): SessionEntry
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
    public function setID(string $id): SessionEntry
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
    public function setName(string $name): SessionEntry
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
    public function setNode(string $node): SessionEntry
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
    public function setChecks(array $checks): SessionEntry
    {
        $this->Checks = [];
        foreach ($checks as $check) {
            $this->addCheck($check);
        }
        return $this;
    }

    public function addCheck(string $check): SessionEntry
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
    public function setLockDelay(Time\Duration $lockDelay): SessionEntry
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
    public function setBehavior(string $behavior): SessionEntry
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
    public function setTTL(string $ttl): SessionEntry
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
     * @return SessionEntry
     */
    public function setNamespace(string $namespace): SessionEntry
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
     * @return $this
     */
    public function addNodeCheck(string $check): SessionEntry
    {
        $this->NodeChecks[] = $check;
        return $this;
    }

    /**
     * @param string[] $nodeChecks
     * @return SessionEntry
     */
    public function setNodeChecks(array $nodeChecks): SessionEntry
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
    public function addServiceCheck(ServiceCheck $check): SessionEntry
    {
        $this->ServiceChecks[] = $check;
        return $this;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Session\ServiceCheck[] $serviceChecks
     * @return SessionEntry
     */
    public function setServiceChecks(array $serviceChecks): SessionEntry
    {
        $this->ServiceChecks = [];
        foreach ($serviceChecks as $check) {
            $this->addServiceCheck($check);
        }
        return $this;
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
        $ms = intval($ns / Time::Millisecond);
        if (0 < $ns && 0 === $ms) {
            return '1ms';
        }
        return sprintf('%dms', $ms);
    }


    /**
     * @return array
     */
    public function _toAPIPayload(): array
    {
        $a = self::jsonSerialize();
        $a['LockDelay'] = self::durToMsec($this->LockDelay);
        return $a;
    }
}