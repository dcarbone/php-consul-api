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

use DCarbone\PHPConsulAPI\AbstractConsulClient;
use DCarbone\PHPConsulAPI\QueryOptions;

/**
 * Class AgentClient
 * @package DCarbone\PHPConsulAPI\Agent
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
     * @param AgentCheckRegistration $agentCheckRegistration
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function checkRegister(AgentCheckRegistration $agentCheckRegistration, QueryOptions $queryOptions = null)
    {
        $this->execute('put', 'v1/agent/check/register', $queryOptions, json_encode($agentCheckRegistration));

        return $this->requireOK();
    }

    /**
     * @param string $checkID
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function checkDeregister($checkID, QueryOptions $queryOptions = null)
    {
        $this->execute('put', sprintf('v1/agent/check/deregister/%s', rawurlencode($checkID)), $queryOptions);

        return $this->requireOK();
    }

    /**
     * Set non-ttl check's state to passing with optional note
     *
     * @param string $checkID
     * @param null|string $note
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function checkPass($checkID, $note = null, QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $queryOptions['note'] = $note;
        
        $this->execute('get', sprintf('v1/agent/check/pass/%s', rawurlencode($checkID)), $queryOptions);

        return $this->requireOK();
    }

    /**
     * Set non-ttl check's state to warning with optional note
     *
     * @param string $checkID
     * @param null|string $note
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function checkWarn($checkID, $note = null, QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $queryOptions['note'] = $note;

        $this->execute('get', sprintf('v1/agent/check/warn/%s', rawurlencode($checkID)), $queryOptions);

        return $this->requireOK();
    }

    /**
     * Set non-ttl check's state to critical with optional note
     *
     * @param string $checkID
     * @param null|string $note
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function checkFail($checkID, $note = null, QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $queryOptions['note'] = $note;

        $this->execute('get', sprintf('v1/agent/check/fail/%s', rawurlencode($checkID)), $queryOptions);

        return $this->requireOK();
    }

    /**
     * Set ttl check status to passing with optional note
     *
     * @param string $checkID
     * @param string $note
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function passTTLCheck($checkID, $note, QueryOptions $queryOptions = null)
    {
        return $this->updateTTLCheck($checkID, $note, 'passing', $queryOptions);
    }

    /**
     * Set ttl check status to warning with optional note
     *
     * @param string $checkID
     * @param string $note
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function warnTTLCheck($checkID, $note, QueryOptions $queryOptions = null)
    {
        return $this->updateTTLCheck($checkID, $note, 'warning', $queryOptions);
    }

    /**
     * Set ttl check status to critical with optional note
     *
     * @param string $checkID
     * @param string $note
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function failTTLCheck($checkID, $note, QueryOptions $queryOptions = null)
    {
        return $this->updateTTLCheck($checkID, $note, 'critical', $queryOptions);
    }

    /**
     * Set ttl check status to one of your choosing with optional note
     *
     * @param string $checkID
     * @param string $output
     * @param string $status
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function updateTTLCheck($checkID, $output, $status, QueryOptions $queryOptions = null)
    {
        $update = new AgentCheckUpdate(['Output' => $output, 'Status' => $status]);

        $this->execute('put', sprintf('v1/agent/check/update/%s', rawurlencode($checkID)), $queryOptions, json_encode($update));

        return $this->requireOK();
    }

    /**
     * Register a service within Consul
     *
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
     * Remove a service from Consul
     *
     * @param string $serviceID
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function serviceDeregister($serviceID, QueryOptions $queryOptions = null)
    {
        $this->execute('put', sprintf('v1/agent/service/deregister/%s', rawurlencode($serviceID)), $queryOptions);

        return $this->requireOK();
    }

    /**
     * @param string $addr
     * @param bool $wan
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function join($addr, $wan = false, QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $queryOptions['wan'] = $wan;

        $this->execute('put', sprintf('v1/agent/join/%s', $addr), $queryOptions);

        return $this->requireOK();
    }

    /**
     * @param string $node
     * @param QueryOptions|null $queryOptions
     * @return bool
     */
    public function forceLeave($node, QueryOptions $queryOptions = null)
    {
        $this->execute('put', sprintf('v1/agent/force-leave/%s', rawurlencode($node)), $queryOptions);
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