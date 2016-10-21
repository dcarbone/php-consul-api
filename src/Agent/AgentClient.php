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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Request;

/**
 * Class AgentClient
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentClient extends AbstractClient
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
        $r = new Request('get', 'v1/agent/self', $this->c);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err)
            return [null, $qm, $err];

        $this->_self = new AgentSelf($data);

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
        $r = new Request('get', 'v1/agent/checks', $this->c);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err)
            return [null, $err];

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err)
            return [null, $err];

        $checks = array();
        foreach($data as $k => $v)
        {
            $checks[$k] = new AgentCheck($v);
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
        $r = new Request('get', 'v1/agent/services', $this->c);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err)
            return [null, $err];

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err)
            return [null, $err];

        $services = array();
        foreach($data as $k => $v)
        {
            $services[$k] = new AgentService($v);
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
        $r = new Request('get', 'v1/agent/members', $this->c);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err)
            return [null, $err];

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err)
            return [null, $err];

        $members = array();
        foreach($data as $v)
        {
            $members[] = new AgentMember($v);
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
        $r = new Request('put', 'v1/agent/service/register', $this->c);
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
        $r = new Request('put', sprintf('v1/agent/service/deregister/%s', $serviceID), $this->c);

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
        $r = new Request('put', sprintf('v1/agent/check/update/%s', $checkID), $this->c);
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
        $r = new Request('put', 'v1/agent/check/register', $this->c);
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
        $r = new Request('put', sprintf('v1/agent/check/deregister/%s', $checkID), $this->c);

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
        $r = new Request('put', sprintf('v1/agent/join/%s', $addr), $this->c);
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
        $r = new Request('put', sprintf('v1/agent/force-leave/%s', $node), $this->c);

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
        $r = new Request('put', sprintf('v1/agent/service/maintenance/%s', $serviceID), $this->c);
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
        $r = new Request('put', sprintf('v1/agent/service/maintenance/%s', $serviceID), $this->c);
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
        $r = new Request('put', 'v1/agent/maintenance', $this->c);
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
        $r = new Request('put', 'v1/agent/maintenance', $this->c);
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
        $r = new Request('get', sprintf('v1/agent/check/pass/%s', $checkID), $this->c);
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
        $r = new Request('get', sprintf('v1/agent/check/warn/%s', $checkID), $this->c);
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
        $r = new Request('get', sprintf('v1/agent/check/fail/%s', $checkID), $this->c);
        $r->params->set('note', $note);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }
}