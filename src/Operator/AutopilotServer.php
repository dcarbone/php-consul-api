<?php declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AutopilotServer
 */
class AutopilotServer extends AbstractModel implements \JsonSerializable
{
    protected const FIELDS = [
        self::FIELD_LAST_CONTACT    => [
            Hydration::FIELD_CALLBACK => [ReadableDuration::class, 'hydrate'],
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_STABLE_SINCE    => [
            Hydration::FIELD_CALLBACK => Hydration::HYDRATE_TIME,
        ],
        self::FIELD_REDUNDANCY_ZONE => Hydration::OMITEMPTY_STRING_FIELD,
        self::FIELD_UPGRADE_VERSION => Hydration::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_LAST_CONTACT    = 'LastContact';
    private const FIELD_STABLE_SINCE    = 'StableSince';
    private const FIELD_REDUNDANCY_ZONE = 'RedundancyZone';
    private const FIELD_UPGRADE_VERSION = 'UpgradeVersion';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Address = '';
    /** @var string */
    public string $NodeStatus = '';
    /** @var string */
    public string $Version = '';
    /** @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null */
    public ?ReadableDuration $LastContact = null;
    /** @var int */
    public int $LastTerm = 0;
    /** @var int */
    public int $LastIndex = 0;
    /** @var bool */
    public bool $Healthy = false;
    /** @var \DCarbone\Go\Time\Time */
    public Time\Time $StableSince;
    /** @var string */
    public string $RedundancyZone = '';
    /** @var string */
    public string $UpgradeVersion = '';
    /** @var bool */
    public bool $ReadReplica = false;
    /** @var string */
    public string $Status = '';
    /** @var array */
    public array $Meta = [];
    /** @var string */
    public string $NodeType = '';

    /**
     * AutopilotServer constructor.
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return string
     */
    public function getNodeStatus(): string
    {
        return $this->NodeStatus;
    }

    /**
     * @param string $NodeStatus
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setNodeStatus(string $NodeStatus): self
    {
        $this->NodeStatus = $NodeStatus;
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setVersion(string $Version): self
    {
        $this->Version = $Version;
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setHealthy(bool $Healthy): self
    {
        $this->Healthy = $Healthy;
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setStableSince(Time\Time $StableSince): self
    {
        $this->StableSince = $StableSince;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedundancyZone(): string
    {
        return $this->RedundancyZone;
    }

    /**
     * @param string $RedundancyZone
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setRedundancyZone(string $RedundancyZone): self
    {
        $this->RedundancyZone = $RedundancyZone;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpgradeVersion(): string
    {
        return $this->UpgradeVersion;
    }

    /**
     * @param string $UpgradeVersion
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setUpgradeVersion(string $UpgradeVersion): self
    {
        $this->UpgradeVersion = $UpgradeVersion;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReadReplica(): bool
    {
        return $this->ReadReplica;
    }

    /**
     * @param bool $ReadReplica
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setReadReplica(bool $ReadReplica): self
    {
        $this->ReadReplica = $ReadReplica;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setStatus(string $Status): self
    {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->Meta;
    }

    /**
     * @param array $Meta
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setMeta(array $Meta): self
    {
        $this->Meta = $Meta;
        return $this;
    }

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return $this->NodeType;
    }

    /**
     * @param string $NodeType
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer
     */
    public function setNodeType(string $NodeType): self
    {
        $this->NodeType = $NodeType;
        return $this;
    }

    /**
     * @return array|mixed|void
     */
    public function jsonSerialize(): array
    {
        $arr = parent::jsonSerialize();
        if (isset($this->StableSince)) {
            $arr[self::FIELD_STABLE_SINCE] = $this->StableSince->format(Time\Time::DefaultFormat);
        }
        return $arr;
    }
}
