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
use DCarbone\PHPConsulAPI\MetaContainer;

class AutopilotServer extends AbstractModel implements \JsonSerializable
{
    use MetaContainer;

    public string $ID;
    public string $Name;
    public string $Address;
    public string $NodeStatus;
    public string $Version;
    public null|Time\Duration $LastContact;
    public int $LastTerm;
    public int $LastIndex;
    public bool $Healthy;
    public Time\Time $StableSince;
    public string $RedundancyZone;
    public string $UpgradeVersion;
    public bool $ReadReplica;
    public AutopilotServerStatus $Status;
    public AutopilotServerType $NodeType;

    /**
     * @param \stdClass|array<string,string>|null $Meta
     */
    public function __construct(
        string $ID = '',
        string $Name = '',
        string $Address = '',
        string $NodeStatus = '',
        string $Version = '',
        null|string|int|float|\DateInterval|Time\Duration $LastContact = null,
        int $LastTerm = 0,
        int $LastIndex = 0,
        bool $Healthy = false,
        null|Time\Time $StableSince = null,
        string $RedundancyZone = '',
        string $UpgradeVersion = '',
        bool $readReplica = false,
        string|AutopilotServerStatus $status = AutopilotServerStatus::UNDEFINED,
        null|\stdClass|array $Meta = null,
        string|AutopilotServerType $NodeType = AutopilotServerType::UNDEFINED,
    ) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Address = $Address;
        $this->NodeStatus = $NodeStatus;
        $this->Version = $Version;
        $this->setLastContact($LastContact);
        $this->LastTerm = $LastTerm;
        $this->LastIndex = $LastIndex;
        $this->Healthy = $Healthy;
        $this->StableSince = $StableSince ?? new TIme\Time();
        $this->RedundancyZone = $RedundancyZone;
        $this->UpgradeVersion = $UpgradeVersion;
        $this->ReadReplica = $readReplica;
        $this->setStatus($status);
        $this->setMeta($Meta);
        $this->setNodeType($NodeType);
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

    public function getLastContact(): null|Time\Duration
    {
        return $this->LastContact ?? null;
    }

    public function setLastContact(null|string|int|float|\DateInterval|Time\Duration $LastContact): self
    {
        if (null === $LastContact) {
            unset($this->LastContact);
            return $this;
        }
        $this->LastContact = Time::Duration($LastContact);
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

    public function getStatus(): AutopilotServerStatus
    {
        return $this->Status;
    }

    public function setStatus(string|AutopilotServerStatus $Status): self
    {
        $this->Status = is_string($Status) ? AutopilotServerStatus::from($Status) : $Status;
        return $this;
    }

    public function getNodeType(): AutopilotServerType
    {
        return $this->NodeType;
    }

    public function setNodeType(string|AutopilotServerType $NodeType): self
    {
        $this->NodeType = is_string($NodeType) ? AutopilotServerType::from($NodeType) : $NodeType;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('lastContact' === $k) {
                $n->setLastContact($v);
            } elseif ('StableSince' === $k) {
                $n->StableSince = Time\Time::createFromFormat(DATE_RFC3339, $v);
            } elseif ('Meta' === $k) {
                $n->setMeta($v);
            } elseif ('Status' === $k) {
                $n->setStatus($v);
            } elseif ('NodeType' === $k) {
                $n->setNodeType($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ID = $this->ID;
        $out->Name = $this->Name;
        $out->Address = $this->Address;
        $out->NodeStatus = $this->NodeStatus;
        $out->Version = $this->Version;
        $out->lastContact = isset($this->LastContact) ? (string)$this->LastContact : null;
        $out->LastTerm = $this->LastTerm;
        $out->LastIndex = $this->LastIndex;
        $out->Healthy = $this->Healthy;
        $out->StableSince = $this->StableSince;
        if ('' !== $this->RedundancyZone) {
            $out->RedundancyZone = $this->RedundancyZone;
        }
        if ('' !== $this->UpgradeVersion) {
            $out->UpgradeVersion = $this->UpgradeVersion;
        }
        $out->ReadReplica = $this->ReadReplica;
        $out->Meta = $this->getMeta();
        $out->NodeType = $this->NodeType;
        return $out;
    }
}
