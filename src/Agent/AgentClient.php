<?php namespace DCarbone\SimpleConsulPHP\Agent;

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

use DCarbone\SimpleConsulPHP\AbstractConsulClient;

/**
 * Class AgentClient
 * @package DCarbone\SimpleConsulPHP\Agent
 */
class AgentClient extends AbstractConsulClient
{
    /**
     * @return null|AgentCheck[]
     */
    public function checks()
    {
        $data = $this->execute('get', 'v1/agent/checks');
        if (null === $data)
            return null;

        $checks = array();
        foreach($data as $k => $v)
        {
            $checks[$k] = new AgentCheck($v);
        }
        return $checks;
    }

    /**
     * @return null|AgentService[]
     */
    public function services()
    {
        $data = $this->execute('get', 'v1/agent/servies');
        if (null === $data)
            return null;

        $services = array();
        foreach($data as $k => $v)
        {
            $services[$k] = new AgentService($v);
        }
        return $services;
    }

    /**
     * @return null|AgentMember[]
     */
    public function members()
    {
        $data = $this->execute('get', 'v1/agent/members');
        if (null === $data)
            return null;

        $members = array();
        foreach($data as $v)
        {
            $members[] = new AgentMember($v);
        }
        return $members;
    }

    /**
     * @return AgentSelf|null
     */
    public function self()
    {
        $data = $this->execute('get', 'v1/agent/self');
        if (null === $data)
            return null;

        return new AgentSelf($data);
    }
}