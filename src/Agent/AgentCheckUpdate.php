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

/**
 * Class AgentCheckUpdate
 */
class AgentCheckUpdate extends AbstractModel
{
    /** @var string */
    public string $Status = '';
    /** @var string */
    public string $Output = '';

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $status
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheckUpdate
     */
    public function setStatus(string $status): self
    {
        $this->Status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->Output;
    }

    /**
     * @param string $output
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheckUpdate
     */
    public function setOutput(string $output): self
    {
        $this->Output = $output;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return \sprintf('%s: %s', $this->Status, $this->Output);
    }
}
