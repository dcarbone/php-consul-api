<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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
 * Class AutopilotState
 * @package DCarbone\PHPConsulAPI\Operator
 */
class AutopilotState extends AbstractModel
{
    /** @var bool */
    public $Healthy = false;
    /** @var int */
    public $FailureTolerance = 0;
    /** @var int */
    public $OptimisticFailureTolerance = 0;
    /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotState[]|null */
    public $Servers = null;
    /** @var string */
    public $Leader = '';
    /** @var string[]|null */
    public $Voters = null;
    /** @var string[]|null */
    public $ReadReplicas = null;
    /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotZone[]|null */
    public $RedundancyZone = null;
    /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade|null */
    public $Upgrade = null;

    /**
     * AutopilotState constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        if (is_array($this->Servers)) {
            foreach ($this->Servers as &$v) {
                if (is_array($v)) {
                    $v = new AutopilotState($v);
                }
            }
        }
        if (is_array($this->RedundancyZone)) {
            foreach ($this->RedundancyZone as &$v) {
                if (is_array($v)) {
                    $v = new AutopilotZone($v);
                }
            }
        }
        if (is_array($this->Upgrade)) {
            $this->Upgrade = new AutopilotUpgrade($this->Upgrade);
        }
    }

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
    public function getServers(): ?array
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
    public function getRedundancyZone(): ?array
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