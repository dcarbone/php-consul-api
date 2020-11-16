<?php namespace DCarbone\PHPConsulAPI\Session;

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
class SessionEntry extends AbstractModel {
    /** @var int */
    public $CreateIndex = 0;
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $Node = '';
    /** @var string[] */
    public $Checks = [];
    /** @var \DCarbone\Go\Time\Duration */
    public $LockDelay = null;
    /** @var string */
    public $Behavior = '';
    /** @var string */
    public $TTL = '';

    /**
     * SessionEntry constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);
        if (!($this->LockDelay instanceof Time\Duration)) {
            $this->LockDelay = new Time\Duration($this->LockDelay ?? 0);
        }
    }

    /**
     * @return int
     */
    public function getCreateIndex(): int {
        return $this->CreateIndex;
    }

    /**
     * @param int $createIndex
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setCreateIndex(int $createIndex): SessionEntry {
        $this->CreateIndex = $createIndex;
        return $this;
    }

    /**
     * @return string
     */
    public function getID(): string {
        return $this->ID;
    }

    /**
     * @param string $id
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setID(string $id): SessionEntry {
        $this->ID = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->Name;
    }

    /**
     * @param string $name
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setName(string $name): SessionEntry {
        $this->Name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getNode(): string {
        return $this->Node;
    }

    /**
     * @param string $node
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setNode(string $node): SessionEntry {
        $this->Node = $node;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getChecks(): array {
        return $this->Checks;
    }

    /**
     * @param string[] $checks
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setChecks(array $checks): SessionEntry {
        $this->Checks = [];
        foreach ($checks as $check) {
            $this->addCheck($check);
        }
        return $this;
    }

    public function addCheck(string $check): SessionEntry {
        $this->Checks[] = $check;
        return $this;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getLockDelay(): Time\Duration {
        return $this->LockDelay;
    }

    /**
     * @param \DCarbone\Go\Time\Duration $lockDelay
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setLockDelay(Time\Duration $lockDelay): SessionEntry {
        $this->LockDelay = $lockDelay;
        return $this;
    }

    /**
     * @return string
     */
    public function getBehavior(): string {
        return $this->Behavior;
    }

    /**
     * @param string $behavior
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setBehavior(string $behavior): SessionEntry {
        $this->Behavior = $behavior;
        return $this;
    }

    /**
     * @return string
     */
    public function getTTL(): string {
        return $this->TTL;
    }

    /**
     * @param string $ttl
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public function setTTL(string $ttl): SessionEntry {
        $this->TTL = $ttl;
        return $this;
    }
}