<?php namespace DCarbone\PHPConsulAPI\Operator;

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
     * @param bool $cleanupDeadServers
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setCleanupDeadServers(bool $cleanupDeadServers): AutopilotConfiguration {
        $this->CleanupDeadServers = $cleanupDeadServers;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastContactThreshold(): string {
        return $this->LastContactThreshold;
    }

    /**
     * @param string $lastContactThreshold
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setLastContactThreshold(string $lastContactThreshold): AutopilotConfiguration {
        $this->LastContactThreshold = $lastContactThreshold;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxTrailingLogs(): int {
        return $this->MaxTrailingLogs;
    }

    /**
     * @param int $maxTrailingLogs
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setMaxTrailingLogs(int $maxTrailingLogs): AutopilotConfiguration {
        $this->MaxTrailingLogs = $maxTrailingLogs;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerStabilizationTime(): string {
        return $this->ServerStabilizationTime;
    }

    /**
     * @param string $serverStabilizationTime
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setServerStabilizationTime(string $serverStabilizationTime): AutopilotConfiguration {
        $this->ServerStabilizationTime = $serverStabilizationTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedundancyZoneTag(): string {
        return $this->RedundancyZoneTag;
    }

    /**
     * @param string $redundancyZoneTag
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setRedundancyZoneTag(string $redundancyZoneTag): AutopilotConfiguration {
        $this->RedundancyZoneTag = $redundancyZoneTag;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisableUpgradeMigration(): bool {
        return $this->DisableUpgradeMigration;
    }

    /**
     * @param bool $disableUpgradeMigration
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setDisableUpgradeMigration(bool $disableUpgradeMigration): AutopilotConfiguration {
        $this->DisableUpgradeMigration = $disableUpgradeMigration;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpgradeVersionTag(): string {
        return $this->UpgradeVersionTag;
    }

    /**
     * @param string $upgradeVersionTag
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setUpgradeVersionTag(string $upgradeVersionTag): AutopilotConfiguration {
        $this->UpgradeVersionTag = $upgradeVersionTag;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateIndex(): int {
        return $this->CreateIndex;
    }

    /**
     * @param int $createIndex
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setCreateIndex(int $createIndex): AutopilotConfiguration {
        $this->CreateIndex = $createIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getModifyIndex(): int {
        return $this->ModifyIndex;
    }

    /**
     * @param int $modifyIndex
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration
     */
    public function setModifyIndex(int $modifyIndex): AutopilotConfiguration {
        $this->ModifyIndex = $modifyIndex;
        return $this;
    }
}