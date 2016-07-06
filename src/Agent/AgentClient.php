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
use DCarbone\SimpleConsulPHP\QueryOptions;

/**
 * Class AgentClient
 * @package DCarbone\SimpleConsulPHP\Agent
 */
class AgentClient extends AbstractConsulClient
{
    /**
     * @param QueryOptions $queryOptions
     * @return AgentCheck[]|null
     */
    public function checks(QueryOptions $queryOptions = null)
    {
        $data = $this->execute('get', 'v1/agent/checks', $queryOptions);
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
     * @param QueryOptions $queryOptions
     * @return AgentService[]|null
     */
    public function services(QueryOptions $queryOptions = null)
    {
        $data = $this->execute('get', 'v1/agent/services', $queryOptions);
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
     * @param QueryOptions $queryOptions
     * @return AgentMember[]|null
     */
    public function members(QueryOptions $queryOptions = null)
    {
        $data = $this->execute('get', 'v1/agent/members', $queryOptions);
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
     * @param QueryOptions $queryOptions
     * @return AgentSelf|null
     */
    public function self(QueryOptions $queryOptions = null)
    {
        $data = $this->execute('get', 'v1/agent/self', $queryOptions);
        if (null === $data)
            return null;

        return new AgentSelf($data);
    }

    /**
     * @param AgentServiceRegistration $agentServiceRegistration
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function serviceRegister(AgentServiceRegistration $agentServiceRegistration, QueryOptions $queryOptions = null)
    {
        $this->execute('put', 'v1/agent/service/register', $queryOptions, json_encode($agentServiceRegistration));
        
        return $this->requireOK();
    }

    /**
     * @param string $serviceID
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function serviceDeregister($serviceID, QueryOptions $queryOptions = null)
    {
        $this->execute('put', sprintf('v1/agent/service/deregister/%s', rawurlencode($serviceID)), $queryOptions);

        return $this->requireOK();
    }

    public function join($addr, $wan = false, QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $queryOptions['wan'] = $wan;

        $this->execute('put', sprintf('v1/agent/join/%s', $addr), $queryOptions);

        return $this->requireOK();
    }

    /**
     * @param string $serviceID
     * @param string|null $reason
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function enableServiceMaintenance($serviceID, $reason = null, QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();
        
        $queryOptions['enable'] = 'true';
        $queryOptions['reason'] = $reason;

        $this->execute('put', sprintf('v1/agent/service/maintenance/%s', rawurlencode($serviceID)), $queryOptions);

        return $this->requireOK();
    }

    /**
     * @param string $serviceID
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function disableServiceMaintenance($serviceID, QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $queryOptions['enable'] = 'false';

        $this->execute('put', sprintf('v1/agent/service/maintenance/%s', rawurlencode($serviceID)), $queryOptions);

        return $this->requireOK();
    }

    /**
     * @param string|null $reason
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function enableNodeMaintenance($reason = null, QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $queryOptions['enable'] = 'true';
        $queryOptions['reason'] = $reason;

        $this->execute('put', 'v1/agent/maintenance', $queryOptions);

        return $this->requireOK();
    }

    /**
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function disableNodeMaintenance(QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $queryOptions['enable'] = 'false';

        $this->execute('put', 'v1/agent/maintenance', $queryOptions);

        return $this->requireOK();
    }
}