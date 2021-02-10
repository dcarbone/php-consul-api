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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AutopilotConfiguration
 */
class AutopilotConfiguration extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_LAST_CONTACT_THRESHOLD    => [
            Hydration::FIELD_CALLBACK => [ReadableDuration::class, 'hydrate'],
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_SERVER_STABILIZATION_TIME => [
            Hydration::FIELD_CALLBACK => [ReadableDuration::class, 'hydrate'],
            Hydration::FIELD_NULLABLE => true,
        ],
    ];

    private const FIELD_LAST_CONTACT_THRESHOLD    = 'LastContactThreshold';
    private const FIELD_SERVER_STABILIZATION_TIME = 'ServerStabilizationTime';

    /** @var bool */
    public bool $CleanupDeadServers = false;
    /** @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null */
    public ?ReadableDuration $LastContactThreshold = null;
    /** @var int */
    public int $MaxTrailingLogs = 0;
    /** @var int */
    public int $MinQuorum = 0;
    /** @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null */
    public ?ReadableDuration $ServerStabilizationTime = null;
    /** @var string */
    public string $RedundancyZoneTag = '';
    /** @var bool */
    public bool $DisableUpgradeMigration = false;
    /** @var string */
    public string $UpgradeVersionTag = '';
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;

    /**
     * @return bool
     */
    public function isCleanupDeadServers(): bool
    {
        return $this->CleanupDeadServers;
    }

    /**
     * @param bool $CleanupDeadServers
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setCleanupDeadServers(bool $CleanupDeadServers): self
    {
        $this->CleanupDeadServers = $CleanupDeadServers;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null
     */
    public function getLastContactThreshold(): ?ReadableDuration
    {
        return $this->LastContactThreshold;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null $LastContactThreshold
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setLastContactThreshold(?ReadableDuration $LastContactThreshold): self
    {
        $this->LastContactThreshold = $LastContactThreshold;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxTrailingLogs(): int
    {
        return $this->MaxTrailingLogs;
    }

    /**
     * @param int $MaxTrailingLogs
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setMaxTrailingLogs(int $MaxTrailingLogs): self
    {
        $this->MaxTrailingLogs = $MaxTrailingLogs;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinQuorum(): int
    {
        return $this->MinQuorum;
    }

    /**
     * @param int $MinQuorum
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setMinQuorum(int $MinQuorum): self
    {
        $this->MinQuorum = $MinQuorum;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null
     */
    public function getServerStabilizationTime(): ?ReadableDuration
    {
        return $this->ServerStabilizationTime;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null $ServerStabilizationTime
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setServerStabilizationTime(?ReadableDuration $ServerStabilizationTime): self
    {
        $this->ServerStabilizationTime = $ServerStabilizationTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedundancyZoneTag(): string
    {
        return $this->RedundancyZoneTag;
    }

    /**
     * @param string $RedundancyZoneTag
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setRedundancyZoneTag(string $RedundancyZoneTag): self
    {
        $this->RedundancyZoneTag = $RedundancyZoneTag;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisableUpgradeMigration(): bool
    {
        return $this->DisableUpgradeMigration;
    }

    /**
     * @param bool $DisableUpgradeMigration
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setDisableUpgradeMigration(bool $DisableUpgradeMigration): self
    {
        $this->DisableUpgradeMigration = $DisableUpgradeMigration;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpgradeVersionTag(): string
    {
        return $this->UpgradeVersionTag;
    }

    /**
     * @param string $UpgradeVersionTag
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setUpgradeVersionTag(string $UpgradeVersionTag): self
    {
        $this->UpgradeVersionTag = $UpgradeVersionTag;
        return $this;
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    /**
     * @param int $ModifyIndex
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }
}
