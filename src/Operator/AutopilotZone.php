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

/**
 * Class AutopilotZone
 */
class AutopilotZone extends AbstractModel
{
    /** @var string[] */
    public array $Servers = [];
    /** @var string[] */
    public array $Voters = [];
    /** @var int */
    public int $FailureTolerance = 0;

    /**
     * @return string[]
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    /**
     * @param string[] $Servers
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZone
     */
    public function setServers(array $Servers): self
    {
        $this->Servers = $Servers;
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZone
     */
    public function setVoters(array $Voters): self
    {
        $this->Voters = $Voters;
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZone
     */
    public function setFailureTolerance(int $FailureTolerance): self
    {
        $this->FailureTolerance = $FailureTolerance;
        return $this;
    }
}
