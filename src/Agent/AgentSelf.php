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

use DCarbone\PHPConsulAPI\AbstractDefinedCollection;

/**
 * Class AgentSelf
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentSelf extends AbstractDefinedCollection
{
    /**
     * AgentSelf constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        parent::__construct();
        foreach($data as $k => $v)
        {
            switch($k)
            {
                case 'Config':
                    $this['Config'] = new AgentSelfConfig($v);
                    break;
                case 'Coord':
                    $this['Coord'] = new AgentSelfCoord($v);
                    break;
                case 'Member':
                    $this['Member'] = new AgentMember($v);
                    break;

                default:
                    $this[$k] = $v;
            }
        }
    }

    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Config' => null,
            'Coord' => null,
            'Member' => null,
        );
    }

    /**
     * @return AgentSelfConfig
     */
    public function getConfig()
    {
        return $this['Config'];
    }

    /**
     * @return AgentSelfCoord
     */
    public function getCoord()
    {
        return $this['Coord'];
    }

    /**
     * @return AgentMember
     */
    public function getMember()
    {
        return $this['Member'];
    }
}