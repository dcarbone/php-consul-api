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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\Request;

/**
 * Class AgentClient
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentClient extends AbstractClient {
    /** @var null|array */
    private $_self = null;

    /**
     * @return array(
     * @type array|null agent info or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function self(): array {
        $r = new Request('GET', 'v1/agent/self', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $qm, $err];
        }

        $this->_self = $data;

        return [$this->_self, $qm, null];
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function reload(): ?Error {
        $r = new Request('PUT', 'v1/agent/reload', $this->config);

        return $this->requireOK($this->doRequest($r))[2];
    }

    /**
     * @return array(
     * @type string name of node or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function nodeName(): array {
        if (null === $this->_self) {
            list($_, $_, $err) = $this->self();
            if (null !== $err) {
                return ['', $err];
            }
        }

        if (isset($this->_self['Config']) && isset($this->_self['Config']['NodeName'])) {
            return [$this->_self['Config']['NodeName'], null];
        }

        return ['', null];
    }

    /**
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Agent\AgentCheck[]|null array of agent checks or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function checks(): array {
        $r = new Request('GET', 'v1/agent/checks', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err) {
            return [null, $err];
        }

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $err];
        }

        $checks = [];
        foreach ($data as $k => $v) {
            $checks[$k] = new AgentCheck($v);
        }

        return [$checks, null];
    }

    /**
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Agent\AgentService[]|null list of agent services or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function services(): array {
        $r = new Request('GET', 'v1/agent/services', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err) {
            return [null, $err];
        }

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $err];
        }

        $services = [];
        foreach ($data as $k => $v) {
            $services[$k] = new AgentService($v);
        }

        return [$services, null];
    }

    /**
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Agent\AgentMember[]|null array of agent members or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function members(): array {
        $r = new Request('GET', 'v1/agent/members', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err) {
            return [null, $err];
        }

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $err];
        }

        $members = [];
        foreach ($data as $v) {
            $members[] = new AgentMember($v);
        }

        return [$members, null];
    }

    /**
     * Register a service within Consul
     *
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration $agentServiceRegistration
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function serviceRegister(AgentServiceRegistration $agentServiceRegistration): ?Error {
        $r = new Request('PUT', 'v1/agent/service/register', $this->config, $agentServiceRegistration);

        return $this->requireOK($this->doRequest($r))[2];
    }

    /**
     * Remove a service from Consul
     *
     * @param string $serviceID
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function serviceDeregister(string $serviceID): ?Error {
        $r = new Request('PUT', sprintf('v1/agent/service/deregister/%s', $serviceID), $this->config);

        return $this->requireOK($this->doRequest($r))[2];
    }

    /**
     * Set ttl check status to passing with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function passTTL(string $checkID, string $note): ?Error {
        return $this->updateTTL($checkID, $note, 'pass');
    }

    /**
     * Set ttl check status to warning with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function warnTTL(string $checkID, string $note): ?Error {
        return $this->updateTTL($checkID, $note, 'warn');
    }

    /**
     * Set ttl check status to critical with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function failTTL(string $checkID, string $note): ?Error {
        return $this->updateTTL($checkID, $note, 'fail');
    }

    /**
     * @param string $checkID
     * @param string $output
     * @param string $status
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function updateTTL(string $checkID, string $output, string $status): ?Error {
        switch ($status) {
            case Consul::HealthPassing:
            case Consul::HealthWarning:
            case Consul::HealthCritical:
                break;
            case 'pass':
                $status = Consul::HealthPassing;
                break;
            case 'warn':
                $status = Consul::HealthWarning;
                break;
            case 'fail':
                $status = Consul::HealthCritical;
                break;
            default:
                return new Error(sprintf(
                    '%s is not a valid status.  Allowed: ["pass", "warn", "fail"]',
                    is_string($status) ? $status : gettype($status)
                ));
        }

        $r = new Request('PUT',
            sprintf('v1/agent/check/update/%s', $checkID),
            $this->config,
            new AgentCheckUpdate(['Output' => $output, 'Status' => $status]));

        return $this->requireOK($this->doRequest($r))[2];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration $agentCheckRegistration
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function checkRegister(AgentCheckRegistration $agentCheckRegistration): ?Error {
        $r = new Request('PUT', 'v1/agent/check/register', $this->config, $agentCheckRegistration);

        return $this->requireOK($this->doRequest($r))[2];
    }

    /**
     * @param string $checkID
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function checkDeregister(string $checkID): ?Error {
        $r = new Request('PUT', sprintf('v1/agent/check/deregister/%s', $checkID), $this->config);

        return $this->requireOK($this->doRequest($r))[2];
    }

    /**
     * @param string $addr
     * @param bool $wan
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function join(string $addr, bool $wan = false): ?Error {
        $r = new Request('PUT', sprintf('v1/agent/join/%s', $addr), $this->config);
        if ($wan) {
            $r->Params->set('wan', '1');
        }

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $node
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function forceLeave(string $node): ?Error {
        $r = new Request('PUT', sprintf('v1/agent/force-leave/%s', $node), $this->config);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $serviceID
     * @param string $reason
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function enableServiceMaintenance(string $serviceID, string $reason = ''): ?Error {
        $r = new Request('PUT', sprintf('v1/agent/service/maintenance/%s', $serviceID), $this->config);
        $r->Params->set('enable', 'true');
        $r->Params->set('reason', $reason);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $serviceID
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function disableServiceMaintenance(string $serviceID): ?Error {
        $r = new Request('PUT', sprintf('v1/agent/service/maintenance/%s', $serviceID), $this->config);
        $r->Params->set('enable', 'false');

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @param string $reason
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function enableNodeMaintenance(string $reason = ''): ?Error {
        $r = new Request('PUT', 'v1/agent/maintenance', $this->config);
        $r->Params->set('enable', 'true');
        $r->Params->set('reason', $reason);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function disableNodeMaintenance(): ?Error {
        $r = new Request('PUT', 'v1/agent/maintenance', $this->config);
        $r->Params->set('enable', 'false');

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function leave(): ?Error {
        $r = new Request('PUT', 'v1/agent/leave', $this->config);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }
}