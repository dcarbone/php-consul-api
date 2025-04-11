<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

class AutopilotServer extends AbstractModel implements \JsonSerializable
{
    protected const FIELDS = [
        self::FIELD_LAST_CONTACT    => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => [ReadableDuration::class, 'unmarshalJSON'],
            Transcoding::FIELD_NULLABLE           => true,
        ],
        self::FIELD_STABLE_SINCE    => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_TIME,
        ],
        self::FIELD_REDUNDANCY_ZONE => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_UPGRADE_VERSION => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_LAST_CONTACT    = 'LastContact';
    private const FIELD_STABLE_SINCE    = 'StableSince';
    private const FIELD_REDUNDANCY_ZONE = 'RedundancyZone';
    private const FIELD_UPGRADE_VERSION = 'UpgradeVersion';

    public string $ID = '';
    public string $Name = '';
    public string $Address = '';
    public string $NodeStatus = '';
    public string $Version = '';
    public ?ReadableDuration $LastContact = null;
    public int $LastTerm = 0;
    public int $LastIndex = 0;
    public bool $Healthy = false;
    public Time\Time $StableSince;
    public string $RedundancyZone = '';
    public string $UpgradeVersion = '';
    public bool $ReadReplica = false;
    public string $Status = '';
    public array $Meta = [];
    public string $NodeType = '';

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->StableSince)) {
            $this->StableSince = Time::New();
        }
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

    public function getAddress(): string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    public function getNodeStatus(): string
    {
        return $this->NodeStatus;
    }

    public function setNodeStatus(string $NodeStatus): self
    {
        $this->NodeStatus = $NodeStatus;
        return $this;
    }

    public function getVersion(): string
    {
        return $this->Version;
    }

    public function setVersion(string $Version): self
    {
        $this->Version = $Version;
        return $this;
    }

    public function getLastContact(): ?ReadableDuration
    {
        return $this->LastContact;
    }

    public function setLastContact(?ReadableDuration $LastContact): self
    {
        $this->LastContact = $LastContact;
        return $this;
    }

    public function getLastTerm(): int
    {
        return $this->LastTerm;
    }

    public function setLastTerm(int $LastTerm): self
    {
        $this->LastTerm = $LastTerm;
        return $this;
    }

    public function getLastIndex(): int
    {
        return $this->LastIndex;
    }

    public function setLastIndex(int $LastIndex): self
    {
        $this->LastIndex = $LastIndex;
        return $this;
    }

    public function isHealthy(): bool
    {
        return $this->Healthy;
    }

    public function setHealthy(bool $Healthy): self
    {
        $this->Healthy = $Healthy;
        return $this;
    }

    public function getStableSince(): Time\Time
    {
        return $this->StableSince;
    }

    public function setStableSince(Time\Time $StableSince): self
    {
        $this->StableSince = $StableSince;
        return $this;
    }

    public function getRedundancyZone(): string
    {
        return $this->RedundancyZone;
    }

    public function setRedundancyZone(string $RedundancyZone): self
    {
        $this->RedundancyZone = $RedundancyZone;
        return $this;
    }

    public function getUpgradeVersion(): string
    {
        return $this->UpgradeVersion;
    }

    public function setUpgradeVersion(string $UpgradeVersion): self
    {
        $this->UpgradeVersion = $UpgradeVersion;
        return $this;
    }

    public function isReadReplica(): bool
    {
        return $this->ReadReplica;
    }

    public function setReadReplica(bool $ReadReplica): self
    {
        $this->ReadReplica = $ReadReplica;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;
        return $this;
    }

    public function getMeta(): array
    {
        return $this->Meta;
    }

    public function setMeta(array $Meta): self
    {
        $this->Meta = $Meta;
        return $this;
    }

    public function getNodeType(): string
    {
        return $this->NodeType;
    }

    public function setNodeType(string $NodeType): self
    {
        $this->NodeType = $NodeType;
        return $this;
    }

    public function jsonSerialize(): array
    {
        $arr = parent::jsonSerialize();
        if (isset($this->StableSince)) {
            $arr[self::FIELD_STABLE_SINCE] = $this->StableSince->format(Time\Time::DefaultFormat);
        }
        return $arr;
    }
}
