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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class AutopilotState extends AbstractType
{
    public bool $Healthy;
    public int $FailureTolerance;
    public int $OptimisticFailureTolerance;
    /** @var array<string, AutopilotServer> */
    public array $Servers;
    public string $Leader;
    /** @var array<string> */
    public array $Voters;
    /** @var array<string> */
    public array $ReadReplicas;
    /** @var array<string, AutopilotZone> */
    public array $RedundancyZone;
    public null|AutopilotUpgrade $Upgrade;

    /**
     * @param array<string, AutopilotServer> $Servers
     * @param array<string> $Voters
     * @param array<string> $ReadReplicas
     * @param array<string, AutopilotZone> $RedundancyZone
     */
    public function __construct(
        bool $Healthy = false,
        int $FailureTolerance = 0,
        int $OptimisticFailureTolerance = 0,
        array $Servers = [],
        string $Leader = '',
        array $Voters = [],
        array $ReadReplicas = [],
        array $RedundancyZone = [],
        null|AutopilotUpgrade $Upgrade = null,
    ) {
        $this->Healthy = $Healthy;
        $this->FailureTolerance = $FailureTolerance;
        $this->OptimisticFailureTolerance = $OptimisticFailureTolerance;
        $this->Servers = $Servers;
        $this->Leader = $Leader;
        $this->Voters = $Voters;
        $this->ReadReplicas = $ReadReplicas;
        $this->RedundancyZone = $RedundancyZone;
        $this->Upgrade = $Upgrade;
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

    public function getFailureTolerance(): int
    {
        return $this->FailureTolerance;
    }

    public function setFailureTolerance(int $FailureTolerance): self
    {
        $this->FailureTolerance = $FailureTolerance;
        return $this;
    }

    public function getOptimisticFailureTolerance(): int
    {
        return $this->OptimisticFailureTolerance;
    }

    public function setOptimisticFailureTolerance(int $OptimisticFailureTolerance): self
    {
        $this->OptimisticFailureTolerance = $OptimisticFailureTolerance;
        return $this;
    }

    /**
     * @return array<string, AutopilotServer>
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    /**
     * @param array<string, AutopilotServer> $Servers
     */
    public function setServers(array $Servers): self
    {
        $this->Servers = $Servers;
        return $this;
    }

    public function getLeader(): string
    {
        return $this->Leader;
    }

    public function setLeader(string $Leader): self
    {
        $this->Leader = $Leader;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getVoters(): array
    {
        return $this->Voters;
    }

    /**
     * @param array<string> $Voters
     */
    public function setVoters(array $Voters): self
    {
        $this->Voters = $Voters;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getReadReplicas(): array
    {
        return $this->ReadReplicas;
    }

    /**
     * @param array<string> $ReadReplicas
     */
    public function setReadReplicas(array $ReadReplicas): self
    {
        $this->ReadReplicas = $ReadReplicas;
        return $this;
    }

    /**
     * @return array<string, AutopilotZone>
     */
    public function getRedundancyZone(): array
    {
        return $this->RedundancyZone;
    }

    /**
     * @param array<string, AutopilotZone> $RedundancyZone
     */
    public function setRedundancyZone(array $RedundancyZone): self
    {
        $this->RedundancyZone = $RedundancyZone;
        return $this;
    }

    public function getUpgrade(): null|AutopilotUpgrade
    {
        return $this->Upgrade;
    }

    public function setUpgrade(null|AutopilotUpgrade $Upgrade): self
    {
        $this->Upgrade = $Upgrade;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Servers' === $k) {
                $n->Servers = [];
                foreach ($v as $sk => $sv) {
                    $n->Servers[$sk] = AutopilotServer::jsonUnserialize($sv);
                }
            } elseif ('RedundancyZone' === $k) {
                $n->RedundancyZone = [];
                foreach ($v as $zk => $zv) {
                    $n->RedundancyZone[$zk] = AutopilotZone::jsonUnserialize($zv);
                }
            } elseif ('Upgrade' === $k) {
                $n->Upgrade = null === $v ? null : AutopilotUpgrade::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Healthy = $this->Healthy;
        $out->FailureTolerance = $this->FailureTolerance;
        $out->OptimisticFailureTolerance = $this->OptimisticFailureTolerance;
        $out->Servers = $this->Servers;
        $out->Leader = $this->Leader;
        $out->Voters = $this->Voters;
        if ([] !== $this->ReadReplicas) {
            $out->ReadReplicas = $this->ReadReplicas;
        }
        if ([] !== $this->RedundancyZone) {
            $out->RedundancyZone = $this->RedundancyZone;
        }
        if (null !== $this->Upgrade) {
            $out->Upgrade = $this->Upgrade;
        }
        return $out;
    }
}
