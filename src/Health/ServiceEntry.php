<?php namespace DCarbone\PHPConsulAPI\Health;

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
     * @return string
     */
    public function getNode()
    {
        return $this->Node;
    }

    /**
     * @return AgentService
     */
    public function getService()
    {
        return $this->Service;
    }

    /**
     * @return HealthCheck[]
     */
    public function getChecks()
    {
        return $this->Checks;
    }
}