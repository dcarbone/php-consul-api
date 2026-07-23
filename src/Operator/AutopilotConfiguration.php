<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class AutopilotConfiguration extends AbstractType
{
    public bool $CleanupDeadServers;
    public null|Time\Duration $LastContactThreshold;
    public int $MaxTrailingLogs;
    public int $MinQuorum;
    public null|Time\Duration $ServerStabilizationTime;
    public string $RedundancyZoneTag;
    public bool $DisableUpgradeMigration;
    public string $UpgradeVersionTag;
    public int $CreateIndex;
    public int $ModifyIndex;

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        bool $CleanupDeadServers = false,
        null|string|int|float|\DateInterval|Time\Duration $LastContactThreshold = null,
        int $MaxTrailingLogs = 0,
        int $MinQuorum = 0,
        null|string|int|float|\DateInterval|Time\Duration $ServerStabilizationTime = null,
        string $RedundancyZoneTag = '',
        bool $DisableUpgradeMigration = false,
        string $UpgradeVersionTag = '',
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->CleanupDeadServers = $CleanupDeadServers;
        $this->setLastContactThreshold($LastContactThreshold);
        $this->MaxTrailingLogs = $MaxTrailingLogs;
        $this->MinQuorum = $MinQuorum;
        $this->setServerStabilizationTime($ServerStabilizationTime);
        $this->RedundancyZoneTag = $RedundancyZoneTag;
        $this->DisableUpgradeMigration = $DisableUpgradeMigration;
        $this->UpgradeVersionTag = $UpgradeVersionTag;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
    }

    public function isCleanupDeadServers(): bool
    {
        return $this->CleanupDeadServers;
    }

    public function setCleanupDeadServers(bool $CleanupDeadServers): self
    {
        $this->CleanupDeadServers = $CleanupDeadServers;
        return $this;
    }

    public function getLastContactThreshold(): null|Time\Duration
    {
        return $this->LastContactThreshold;
    }

    public function setLastContactThreshold(null|string|int|float|\DateInterval|Time\Duration $LastContactThreshold): self
    {
        if (null === $LastContactThreshold) {
            $this->LastContactThreshold = null;
            return $this;
        }
        $this->LastContactThreshold = Time::Duration($LastContactThreshold);
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

    public function getServerStabilizationTime(): null|Time\Duration
    {
        return $this->ServerStabilizationTime;
    }

    public function setServerStabilizationTime(null|string|int|float|\DateInterval|Time\Duration $ServerStabilizationTime): self
    {
        if (null === $ServerStabilizationTime) {
            $this->ServerStabilizationTime = null;
            return $this;
        }
        $this->ServerStabilizationTime = Time::Duration($ServerStabilizationTime);
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('LastContactThreshold' === $k) {
                $n->setLastContactThreshold($v);
            } elseif ('ServerStabilizationTime' === $k) {
                $n->setServerStabilizationTime($v);
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->CleanupDeadServers = $this->CleanupDeadServers;
        if (null !== $this->LastContactThreshold) {
            $out->LastContactThreshold = (string)$this->LastContactThreshold;
        }
        $out->MaxTrailingLogs = $this->MaxTrailingLogs;
        $out->MinQuorum = $this->MinQuorum;
        if (null !== $this->ServerStabilizationTime) {
            $out->ServerStabilizationTime = (string)$this->ServerStabilizationTime;
        }
        if ('' !== $this->RedundancyZoneTag) {
            $out->RedundancyZoneTag = $this->RedundancyZoneTag;
        }
        $out->DisableUpgradeMigration = $this->DisableUpgradeMigration;
        if ('' !== $this->UpgradeVersionTag) {
            $out->UpgradeVersionTag = $this->UpgradeVersionTag;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
