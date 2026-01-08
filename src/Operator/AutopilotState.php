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
use DCarbone\PHPConsulAPI\Transcoding;

class AutopilotState extends AbstractType
{
    protected const FIELDS = [
        self::FIELD_SERVERS         => [
            Transcoding::FIELD_TYPE  => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS => AutopilotServer::class,
        ],
        self::FIELD_READ_REPLICAS   => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::STRING,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_REDUNDANCY_ZONE => [
            Transcoding::FIELD_TYPE      => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS     => AutopilotZone::class,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
        self::FIELD_UPGRADE         => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => AutopilotUpgrade::class,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
    ];

    private const FIELD_SERVERS         = 'Servers';
    private const FIELD_READ_REPLICAS   = 'ReadReplicas';
    private const FIELD_REDUNDANCY_ZONE = 'RedundancyZone';
    private const FIELD_UPGRADE         = 'Upgrade';

    public bool $Healthy;
    public int $FailureTolerance;
    public int $OptimisticFailureTolerance;
    public array $Servers;
    public string $Leader;
    public array $Voters;
    public array $ReadReplicas;
    public array $RedundancyZone;
    public ?AutopilotUpgrade $Upgrade;

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

    public function getServers(): array
    {
        return $this->Servers;
    }

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

    public function getVoters(): array
    {
        return $this->Voters;
    }

    public function setVoters(array $Voters): self
    {
        $this->Voters = $Voters;
        return $this;
    }

    public function getReadReplicas(): array
    {
        return $this->ReadReplicas;
    }

    public function setReadReplicas(array $ReadReplicas): self
    {
        $this->ReadReplicas = $ReadReplicas;
        return $this;
    }

    public function getRedundancyZone(): array
    {
        return $this->RedundancyZone;
    }

    public function setRedundancyZone(array $RedundancyZone): self
    {
        $this->RedundancyZone = $RedundancyZone;
        return $this;
    }

    public function getUpgrade(): ?AutopilotUpgrade
    {
        return $this->Upgrade;
    }

    public function setUpgrade(?AutopilotUpgrade $Upgrade): self
    {
        $this->Upgrade = $Upgrade;
        return $this;
    }
}
