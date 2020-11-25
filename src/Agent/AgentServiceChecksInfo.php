<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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
use DCarbone\PHPConsulAPI\Health\HealthChecks;

/**
 * Class AgentServiceChecksInfo
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentServiceChecksInfo extends AbstractModel
{
    /** @var string */
    public $AggregatedStatus = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService|null */
    public $Service = null;
    /** @var \DCarbone\PHPConsulAPI\Health\HealthChecks|null */
    public $Checks = null;

    /**
     * AgentServiceChecksInfo constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (isset($data['AggregatedStatus'])) {
            $this->AggregatedStatus = $data['AggregatedStatus'];
        }
        if (isset($data['Service'])) {
            $this->Service = new AgentService($data['Service']);
        }
        if (isset($data['Checks'])) {
            $this->Checks = new HealthChecks($data['Checks']);
        }
    }

    /**
     * @return string
     */
    public function getAggregatedStatus(): string
    {
        return $this->AggregatedStatus;
    }

    /**
     * @param string $AggregatedStatus
     * @return AgentServiceChecksInfo
     */
    public function setAggregatedStatus(string $AggregatedStatus): AgentServiceChecksInfo
    {
        $this->AggregatedStatus = $AggregatedStatus;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService|null
     */
    public function getService(): ?AgentService
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentService|null $Service
     * @return AgentServiceChecksInfo
     */
    public function setService(?AgentService $Service): AgentServiceChecksInfo
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthChecks|null
     */
    public function getChecks(): ?HealthChecks
    {
        return $this->Checks;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Health\HealthChecks|null $Checks
     * @return AgentServiceChecksInfo
     */
    public function setChecks(?HealthChecks $Checks): AgentServiceChecksInfo
    {
        $this->Checks = $Checks;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->AggregatedStatus;
    }
}