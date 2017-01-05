<?php namespace DCarbone\PHPConsulAPI\Agent;

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

/**
 * Class AgentSelf
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentSelf extends AbstractModel
{
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentSelfConfig */
    public $Config = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentSelfCoord */
    public $Coord = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentMember */
    public $Member = null;

    /**
     * AgentSelf constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        if (!($this->Config instanceof AgentSelfConfig))
            $this->Config = new AgentSelfConfig((array)$this->Config);
        if (!($this->Coord instanceof AgentSelfCoord))
            $this->Coord = new AgentSelfCoord((array)$this->Coord);
        if (!($this->Member instanceof AgentMember))
            $this->Member = new AgentMember((array)$this->Member);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentSelfConfig
     */
    public function getConfig()
    {
        return $this->Config;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentSelfCoord
     */
    public function getCoord()
    {
        return $this->Coord;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentMember
     */
    public function getMember()
    {
        return $this->Member;
    }
}