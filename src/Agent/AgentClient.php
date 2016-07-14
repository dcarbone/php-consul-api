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

use DCarbone\PHPConsulAPI\AbstractApiClient;
use DCarbone\PHPConsulAPI\HttpRequest;
use DCarbone\PHPConsulAPI\Hydrator;

/**
 * Class AgentClient
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentClient extends AbstractApiClient
{
    /** @var null|AgentSelf */
    private $_self = null;

    /**
     * @return array(
     * @type AgentSelf|null agent info or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function self()
    {
        $r = new HttpRequest('get', 'v1/agent/self', $this->_Config);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        $this->_self = Hydrator::AgentSelf($data);

        return [$this->_self, $qm, null];
    }

    /**
     * @return array(
     *  @type string name of node or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function nodeName()
    {
        if (null === $this->_self)
        {
            list($_, $_, $err) = $this->self();
            if (null !== $err)
                return ['', $err];
        }

        return [$this->_self->Config->NodeName, null];
    }

    /**
     * @return array(
     *  @type AgentCheck[]|null array of agent checks or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function checks()
    {
        $r = new HttpRequest('get', 'v1/agent/checks', $this->_Config);

        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err)
            return [null, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $err];

        $checks = array();
        foreach($data as $k => $v)
        {
            $checks[$k] = Hydrator::AgentCheck($v);
        }

        return [$checks, null];
    }

    /**
     * @return array(
     *  @type AgentService[]|null list of agent services or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function services()
    {
        $r = new HttpRequest('get', 'v1/agent/services', $this->_Config);

        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err)
            return [null, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $err];

        $services = array();
        foreach($data as $k => $v)
        {
            $services[$k] = Hydrator::AgentService($v);
        }

        return [$services, null];
    }

    /**
     * @return array(
     *  @type AgentMember[]|null array of agent members or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function members()
    {
        $r = new HttpRequest('get', 'v1/agent/members', $this->_Config);
        
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err)
            return [null, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $err];

        $members = array();
        foreach($data as $v)
        {
            $members[] = Hydrator::AgentMember($v);
        }

        return [$members, null];
    }

    /**
     * Register a service within Consul
     *
     * @param AgentServiceRegistration $agentServiceRegistration
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function serviceRegister(AgentServiceRegistration $agentServiceRegistration)
    {
        $r = new HttpRequest('put', 'v1/agent/service/register', $this->_Config);
        $r->body = ($agentServiceRegistration);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * Remove a service from Consul
     *
     * @param string $serviceID
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function serviceDeregister($serviceID)
    {
        $r = new HttpRequest('put', sprintf('v1/agent/service/deregister/%s', rawurlencode($serviceID)), $this->_Config);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * Set ttl check status to passing with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function passTTL($checkID, $note)
    {
        return $this->updateTTL($checkID, $note, 'passing');
    }

    /**
     * Set ttl check status to warning with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function warnTTL($checkID, $note)
    {
        return $this->updateTTL($checkID, $note, 'warning');
    }

    /**
     * Set ttl check status to critical with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function failTTL($checkID, $note)
    {
        return $this->updateTTL($checkID, $note, 'critical');
    }

    /**
     * Set ttl check status to one of your choosing with optional note
     *
     * @param string $checkID
     * @param string $output
     * @param string $status
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function updateTTL($checkID, $output, $status)
    {
        $r = new HttpRequest('put', sprintf('v1/agent/check/update/%s', rawurlencode($checkID)), $this->_Config);
        $r->body = (new AgentCheckUpdate(['Output' => $output, 'Status' => $status]));

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param AgentCheckRegistration $agentCheckRegistration
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function checkRegister(AgentCheckRegistration $agentCheckRegistration)
    {
        $r = new HttpRequest('put', 'v1/agent/check/register', $this->_Config);
        $r->body = ($agentCheckRegistration);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $checkID
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function checkDeregister($checkID)
    {
        $r = new HttpRequest('put', sprintf('v1/agent/check/deregister/%s', rawurlencode($checkID)), $this->_Config);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $addr
     * @param bool $wan
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function join($addr, $wan = false)
    {
        $r = new HttpRequest('put', sprintf('v1/agent/join/%s', rawurlencode($addr)), $this->_Config);
        if ($wan)
            $r->params->set('wan', 1);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $node
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function forceLeave($node)
    {
        $r = new HttpRequest('put', sprintf('v1/agent/force-leave/%s', rawurlencode($node)), $this->_Config);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $serviceID
     * @param string $reason
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function enableServiceMaintenance($serviceID, $reason = '')
    {
        $r = new HttpRequest('put', sprintf('v1/agent/service/maintenance/%s', rawurlencode($serviceID)), $this->_Config);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $serviceID
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function disableServiceMaintenance($serviceID)
    {
        $r = new HttpRequest('put', sprintf('v1/agent/service/maintenance/%s', rawurlencode($serviceID)), $this->_Config);
        $r->params->set('enable', 'false');

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $reason
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function enableNodeMaintenance($reason = '')
    {
        $r = new HttpRequest('put', 'v1/agent/maintenance', $this->_Config);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function disableNodeMaintenance()
    {
        $r = new HttpRequest('put', 'v1/agent/maintenance', $this->_Config);
        $r->params->set('enable', 'false');

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * Set non-ttl check's state to passing with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function checkPass($checkID, $note = '')
    {
        $r = new HttpRequest('get', sprintf('v1/agent/check/pass/%s', rawurlencode($checkID)), $this->_Config);
        $r->params->set('note', $note);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * Set non-ttl check's state to warning with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function checkWarn($checkID, $note = '')
    {
        $r = new HttpRequest('get', sprintf('v1/agent/check/warn/%s', rawurlencode($checkID)), $this->_Config);
        $r->params->set('note', $note);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * Set non-ttl check's state to critical with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function checkFail($checkID, $note = '')
    {
        $r = new HttpRequest('get', sprintf('v1/agent/check/fail/%s', rawurlencode($checkID)), $this->_Config);
        $r->params->set('note', $note);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }
}