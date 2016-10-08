<?php namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentCheckUpdate extends AbstractModel
{
    /** @var string */
    public $Status = '';
    /** @var string */
    public $Output = '';

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return AgentCheckUpdate
     */
    public function setStatus($Status)
    {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->Output;
    }

    /**
     * @param string $Output
     * @return AgentCheckUpdate
     */
    public function setOutput($Output)
    {
        $this->Output = $Output;
        return $this;
    }
}