<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

/**
 * Class ServerHealth
 */
class ServerHealth extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_LAST_CONTACT => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => [ReadableDuration::class, 'unmarshalJSON'],
            Transcoding::FIELD_NULLABLE           => true,
        ],
        self::FIELD_STABLE_SINCE => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_TIME,
        ],
    ];

    private const FIELD_LAST_CONTACT = 'LastContact';
    private const FIELD_STABLE_SINCE = 'StableSince';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Address = '';
    /** @var string */
    public string $SerfStatus = '';
    /** @var string */
    public string $Version = '';
    /** @var bool */
    public bool $Leader = false;
    /** @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null */
    public ?ReadableDuration $LastContact = null;
    /** @var int */
    public int $LastTerm = 0;
    /** @var int */
    public int $LastIndex = 0;
    /** @var bool */
    public bool $Healthy = false;
    /** @var bool */
    public bool $Voter = false;
    /** @var \DCarbone\Go\Time\Time */
    public Time\Time $StableSince;

    /**
     * ServerHealth constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->StableSince)) {
            $this->StableSince = Time::New();
        }
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
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
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
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return string
     */
    public function getSerfStatus(): string
    {
        return $this->SerfStatus;
    }

    /**
     * @param string $SerfStatus
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setSerfStatus(string $SerfStatus): self
    {
        $this->SerfStatus = $SerfStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->Version;
    }

    /**
     * @param string $Version
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setVersion(string $Version): self
    {
        $this->Version = $Version;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLeader(): bool
    {
        return $this->Leader;
    }

    /**
     * @param bool $Leader
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setLeader(bool $Leader): self
    {
        $this->Leader = $Leader;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null
     */
    public function getLastContact(): ?ReadableDuration
    {
        return $this->LastContact;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null $LastContact
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setLastContact(?ReadableDuration $LastContact): self
    {
        $this->LastContact = $LastContact;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastTerm(): int
    {
        return $this->LastTerm;
    }

    /**
     * @param int $LastTerm
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setLastTerm(int $LastTerm): self
    {
        $this->LastTerm = $LastTerm;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastIndex(): int
    {
        return $this->LastIndex;
    }

    /**
     * @param int $LastIndex
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setLastIndex(int $LastIndex): self
    {
        $this->LastIndex = $LastIndex;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHealthy(): bool
    {
        return $this->Healthy;
    }

    /**
     * @param bool $Healthy
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setHealthy(bool $Healthy): self
    {
        $this->Healthy = $Healthy;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVoter(): bool
    {
        return $this->Voter;
    }

    /**
     * @param bool $Voter
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setVoter(bool $Voter): self
    {
        $this->Voter = $Voter;
        return $this;
    }

    /**
     * @return \DCarbone\Go\Time\Time
     */
    public function getStableSince(): Time\Time
    {
        return $this->StableSince;
    }

    /**
     * @param \DCarbone\Go\Time\Time $StableSince
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth
     */
    public function setStableSince(Time\Time $StableSince): self
    {
        $this->StableSince = $StableSince;
        return $this;
    }
}
