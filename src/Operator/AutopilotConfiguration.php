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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

class AutopilotConfiguration extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_LAST_CONTACT_THRESHOLD    => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => [ReadableDuration::class, 'unmarshalJSON'],
            Transcoding::FIELD_NULLABLE           => true,
        ],
        self::FIELD_SERVER_STABILIZATION_TIME => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => [ReadableDuration::class, 'unmarshalJSON'],
            Transcoding::FIELD_NULLABLE           => true,
        ],
    ];

    private const FIELD_LAST_CONTACT_THRESHOLD    = 'LastContactThreshold';
    private const FIELD_SERVER_STABILIZATION_TIME = 'ServerStabilizationTime';

    public bool $CleanupDeadServers;
    public ?ReadableDuration $LastContactThreshold;
    public int $MaxTrailingLogs;
    public int $MinQuorum;
    public ?ReadableDuration $ServerStabilizationTime;
    public string $RedundancyZoneTag;
    public bool $DisableUpgradeMigration;
    public string $UpgradeVersionTag;
    public int $CreateIndex;
    public int $ModifyIndex;

    public function isCleanupDeadServers(): bool
    {
        return $this->CleanupDeadServers;
    }

    public function setCleanupDeadServers(bool $CleanupDeadServers): self
    {
        $this->CleanupDeadServers = $CleanupDeadServers;
        return $this;
    }

    public function getLastContactThreshold(): ?ReadableDuration
    {
        return $this->LastContactThreshold;
    }

    public function setLastContactThreshold(?ReadableDuration $LastContactThreshold): self
    {
        $this->LastContactThreshold = $LastContactThreshold;
        return $this;
    }

    public function getMaxTrailingLogs(): int
    {
        return $this->MaxTrailingLogs;
    }

    public function setMaxTrailingLogs(int $MaxTrailingLogs): self
    {
        $this->MaxTrailingLogs = $MaxTrailingLogs;
        return $this;
    }

    public function getMinQuorum(): int
    {
        return $this->MinQuorum;
    }

    public function setMinQuorum(int $MinQuorum): self
    {
        $this->MinQuorum = $MinQuorum;
        return $this;
    }

    public function getServerStabilizationTime(): ?ReadableDuration
    {
        return $this->ServerStabilizationTime;
    }

    public function setServerStabilizationTime(?ReadableDuration $ServerStabilizationTime): self
    {
        $this->ServerStabilizationTime = $ServerStabilizationTime;
        return $this;
    }

    public function getRedundancyZoneTag(): string
    {
        return $this->RedundancyZoneTag;
    }

    public function setRedundancyZoneTag(string $RedundancyZoneTag): self
    {
        $this->RedundancyZoneTag = $RedundancyZoneTag;
        return $this;
    }

    public function isDisableUpgradeMigration(): bool
    {
        return $this->DisableUpgradeMigration;
    }

    public function setDisableUpgradeMigration(bool $DisableUpgradeMigration): self
    {
        $this->DisableUpgradeMigration = $DisableUpgradeMigration;
        return $this;
    }

    public function getUpgradeVersionTag(): string
    {
        return $this->UpgradeVersionTag;
    }

    public function setUpgradeVersionTag(string $UpgradeVersionTag): self
    {
        $this->UpgradeVersionTag = $UpgradeVersionTag;
        return $this;
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

    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }
}
