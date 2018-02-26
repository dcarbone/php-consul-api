<?php namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class AutopilotConfiguration
 * @package DCarbone\PHPConsulAPI\Operator
 */
class AutopilotConfiguration extends AbstractModel {
    /** @var bool */
    public $CleanupDeadServers = false;
    /** @var string */
    public $LastContactThreshold = '';
    /** @var int */
    public $MaxTrailingLogs = 0;
    /** @var string */
    public $ServerStabilizationTime = '';
    /** @var string */
    public $RedundancyZoneTag = '';
    /** @var bool */
    public $DisableUpgradeMigration = false;
    /** @var string */
    public $UpgradeVersionTag = '';
    /** @var int */
    public $CreateIndex = 0;
    /** @var int */
    public $ModifyIndex = 0;

    /**
     * @return bool
     */
    public function isCleanupDeadServers(): bool {
        return $this->CleanupDeadServers;
    }

    /**
     * @param bool $CleanupDeadServers
     * @return AutopilotConfiguration
     */
    public function setCleanupDeadServers(bool $CleanupDeadServers): AutopilotConfiguration {
        $this->CleanupDeadServers = $CleanupDeadServers;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastContactThreshold(): string {
        return $this->LastContactThreshold;
    }

    /**
     * @param string $LastContactThreshold
     * @return AutopilotConfiguration
     */
    public function setLastContactThreshold(string $LastContactThreshold): AutopilotConfiguration {
        $this->LastContactThreshold = $LastContactThreshold;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxTrailingLogs(): int {
        return $this->MaxTrailingLogs;
    }

    /**
     * @param int $MaxTrailingLogs
     * @return AutopilotConfiguration
     */
    public function setMaxTrailingLogs(int $MaxTrailingLogs): AutopilotConfiguration {
        $this->MaxTrailingLogs = $MaxTrailingLogs;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerStabilizationTime(): string {
        return $this->ServerStabilizationTime;
    }

    /**
     * @param string $ServerStabilizationTime
     * @return AutopilotConfiguration
     */
    public function setServerStabilizationTime(string $ServerStabilizationTime): AutopilotConfiguration {
        $this->ServerStabilizationTime = $ServerStabilizationTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedundancyZoneTag(): string {
        return $this->RedundancyZoneTag;
    }

    /**
     * @param string $RedundancyZoneTag
     * @return AutopilotConfiguration
     */
    public function setRedundancyZoneTag(string $RedundancyZoneTag): AutopilotConfiguration {
        $this->RedundancyZoneTag = $RedundancyZoneTag;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisableUpgradeMigration(): bool {
        return $this->DisableUpgradeMigration;
    }

    /**
     * @param bool $DisableUpgradeMigration
     * @return AutopilotConfiguration
     */
    public function setDisableUpgradeMigration(bool $DisableUpgradeMigration): AutopilotConfiguration {
        $this->DisableUpgradeMigration = $DisableUpgradeMigration;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpgradeVersionTag(): string {
        return $this->UpgradeVersionTag;
    }

    /**
     * @param string $UpgradeVersionTag
     * @return AutopilotConfiguration
     */
    public function setUpgradeVersionTag(string $UpgradeVersionTag): AutopilotConfiguration {
        $this->UpgradeVersionTag = $UpgradeVersionTag;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateIndex(): int {
        return $this->CreateIndex;
    }

    /**
     * @param int $CreateIndex
     * @return AutopilotConfiguration
     */
    public function setCreateIndex(int $CreateIndex): AutopilotConfiguration {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getModifyIndex(): int {
        return $this->ModifyIndex;
    }

    /**
     * @param int $ModifyIndex
     * @return AutopilotConfiguration
     */
    public function setModifyIndex(int $ModifyIndex): AutopilotConfiguration {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }
}