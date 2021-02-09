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
 * Class AutopilotState
 */
class AutopilotState extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SERVERS         => [
            Hydration::FIELD_TYPE  => Hydration::ARRAY,
            Hydration::FIELD_CLASS => self::class,
        ],
        self::FIELD_REDUNDANCY_ZONE => [
            Hydration::FIELD_TYPE  => Hydration::ARRAY,
            Hydration::FIELD_CLASS => AutopilotZone::class,
        ],
        self::FIELD_UPGRADE         => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => AutopilotUpgrade::class,
        ],
    ];
    private const FIELD_SERVERS         = 'Servers';
    private const FIELD_REDUNDANCY_ZONE = 'RedundancyZone';
    private const FIELD_UPGRADE         = 'Upgrade';

    /** @var bool */
    public bool $Healthy = false;
    /** @var int */
    public int $FailureTolerance = 0;
    /** @var int */
    public int $OptimisticFailureTolerance = 0;
    /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotState[] */
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
     * @return int
     */
    public function getFailureTolerance(): int
    {
        return $this->FailureTolerance;
    }

    /**
     * @return int
     */
    public function getOptimisticFailureTolerance(): int
    {
        return $this->OptimisticFailureTolerance;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotState[]|null
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    /**
     * @return string
     */
    public function getLeader(): string
    {
        return $this->Leader;
    }

    /**
     * @return string[]|null
     */
    public function getVoters(): ?array
    {
        return $this->Voters;
    }

    /**
     * @return string[]|null
     */
    public function getReadReplicas(): ?array
    {
        return $this->ReadReplicas;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZone[]|null
     */
    public function getRedundancyZone(): array
    {
        return $this->RedundancyZone;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade|null
     */
    public function getUpgrade(): ?AutopilotUpgrade
    {
        return $this->Upgrade;
    }
}
