<?php namespace DCarbone\PHPConsulAPI\Health;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentService;

/**
 * Class ServiceEntry
 * @package DCarbone\PHPConsulAPI\Health
 */
class ServiceEntry extends AbstractModel
{
    /** @var string */
    public $Node = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService */
    public $Service = null;
    /** @var \DCarbone\PHPConsulAPI\Health\HealthCheck[] */
    public $Checks = array();

    /**
     * ServiceEntry constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        parent::__construct($data);

        $this->Service = new AgentService((array)$this->Service);
        if (isset($this->Checks))
        {
            for ($i = 0, $cnt = count($this->Checks); $i < $cnt; $i++)
            {
                $this->Checks[$i] = new AgentCheck((array)$this->Checks[$i]);
            }
        }
    }

    /**
     * @return string
     */
    public function getNode()
    {
        return $this->Node;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function getService()
    {
        return $this->Service;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthCheck[]
     */
    public function getChecks()
    {
        return $this->Checks;
    }
}