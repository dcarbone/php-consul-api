<?php

declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class AutopilotState
 */
class AutopilotState extends AbstractModel
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

    /** @var bool */
    public bool $Healthy = false;
    /** @var int */
    public int $FailureTolerance = 0;
    /** @var int */
    public int $OptimisticFailureTolerance = 0;
    /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotServer[] */
    public array $Servers = [];
    /** @var string */
    public string $Leader = '';
    /** @var string[] */
    public array $Voters = [];
    /** @var string[] */
    public array $ReadReplicas = [];
    /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotZone[] */
    public array $RedundancyZone = [];
    /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade|null */
    public ?AutopilotUpgrade $Upgrade = null;

    /**
     * @return bool
     */
    public function isHealthy(): bool
    {
        return $this->Healthy;
    }

    /**
     * @param bool $Healthy
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState
     */
    public function setHealthy(bool $Healthy): self
    {
        $this->Healthy = $Healthy;
        return $this;
    }

    /**
     * @return int
     */
    public function getFailureTolerance(): int
    {
        return $this->FailureTolerance;
    }

    /**
     * @param int $FailureTolerance
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState
     */
    public function setFailureTolerance(int $FailureTolerance): self
    {
        $this->FailureTolerance = $FailureTolerance;
        return $this;
    }

    /**
     * @return int
     */
    public function getOptimisticFailureTolerance(): int
    {
        return $this->OptimisticFailureTolerance;
    }

    /**
     * @param int $OptimisticFailureTolerance
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState
     */
    public function setOptimisticFailureTolerance(int $OptimisticFailureTolerance): self
    {
        $this->OptimisticFailureTolerance = $OptimisticFailureTolerance;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotServer[]
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotServer[] $Servers
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState
     */
    public function setServers(array $Servers): self
    {
        $this->Servers = $Servers;
        return $this;
    }

    /**
     * @return string
     */
    public function getLeader(): string
    {
        return $this->Leader;
    }

    /**
     * @param string $Leader
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState
     */
    public function setLeader(string $Leader): self
    {
        $this->Leader = $Leader;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getVoters(): array
    {
        return $this->Voters;
    }

    /**
     * @param string[] $Voters
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState
     */
    public function setVoters(array $Voters): self
    {
        $this->Voters = $Voters;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getReadReplicas(): array
    {
        return $this->ReadReplicas;
    }

    /**
     * @param string[] $ReadReplicas
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState
     */
    public function setReadReplicas(array $ReadReplicas): self
    {
        $this->ReadReplicas = $ReadReplicas;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZone[]
     */
    public function getRedundancyZone(): array
    {
        return $this->RedundancyZone;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotZone[] $RedundancyZone
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState
     */
    public function setRedundancyZone(array $RedundancyZone): self
    {
        $this->RedundancyZone = $RedundancyZone;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade|null
     */
    public function getUpgrade(): ?AutopilotUpgrade
    {
        return $this->Upgrade;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade|null $Upgrade
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState
     */
    public function setUpgrade(?AutopilotUpgrade $Upgrade): self
    {
        $this->Upgrade = $Upgrade;
        return $this;
    }
}
