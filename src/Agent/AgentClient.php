<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\ValuedStringResponse;

/**
 * Class AgentClient
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentClient extends AbstractClient
{
    /** @var null|array */
    private $_self = null;

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentSelfResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Self(): AgentSelfResponse
    {
        $r = new Request('GET', 'v1/agent/self', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return new AgentSelfResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return new AgentSelfResponse(null, $qm, $err);
        }

        $this->_self = $data;

        return new AgentSelfResponse($data, $qm, null);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfoResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Metrics(): MetricsInfoResponse
    {
        $r = new Request('GET', 'v1/agent/metrics', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return new MetricsInfoResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return new MetricsInfoResponse(null, $err);
        }

        return new MetricsInfoResponse(new MetricsInfo($data), null);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Reload(): ?Error
    {
        $r = new Request('PUT', 'v1/agent/reload', $this->config);

        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ValuedStringResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function NodeName(): ValuedStringResponse
    {
        if (null === $this->_self) {
            [$_, $_, $err] = $this->Self();
            if (null !== $err) {
                return new ValuedStringResponse('', $err);
            }
        }

        if (isset($this->_self['Config']) && isset($this->_self['Config']['NodeName'])) {
            return new ValuedStringResponse($this->_self['Config']['NodeName'], null);
        }

        return new ValuedStringResponse('', null);
    }

    /**
     * @param string $filter
     * @return \DCarbone\PHPConsulAPI\Agent\AgentChecksResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ChecksWithFilter(string $filter): AgentChecksResponse
    {
        $r = new Request('GET', 'v1/agent/checks', $this->config);
        $r->filterQuery($filter);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->requireOK($this->doRequest($r));

        if (null !== $err) {
            return new AgentChecksResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        return new AgentChecksResponse($data, $err);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentChecksResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Checks(): AgentChecksResponse
    {
        return $this->checksWithFilter('');
    }

    /**
     * @param string $filter
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServicesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ServicesWithFilter(string $filter): AgentServicesResponse
    {
        $r = new Request('GET', 'v1/agent/services', $this->config);
        $r->filterQuery($filter);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->requireOK($this->doRequest($r));

        if (null !== $err) {
            return new AgentServicesResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        return new AgentServicesResponse($data, $err);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServicesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Services(): AgentServicesResponse
    {
        return $this->ServicesWithFilter('');
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentMembersResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Members(): AgentMembersResponse
    {
        $r = new Request('GET', 'v1/agent/members', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return new AgentMembersResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        return new AgentMembersResponse($data, $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\MemberOpts $opts
     * @return \DCarbone\PHPConsulAPI\Agent\AgentMembersResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function MemberOpts(MemberOpts $opts): AgentMembersResponse
    {
        $r = new Request('GET', 'v1/agent/members', $this->config);
        $r->params->set('segment', $opts->Segment);
        if ($opts->WAN) {
            $r->params->set('wan', '1');
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return new AgentMembersResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        return new AgentMembersResponse($data, $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration $service
     * @param \DCarbone\PHPConsulAPI\Agent\ServiceRegisterOpts $opts
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ServiceRegisterOpts(AgentServiceRegistration $service, ServiceRegisterOpts $opts): ?Error
    {
        $r = new Request('PUT', 'v1/agent/service/register', $this->config, $service);
        if ($opts->ReplaceExistingChecks) {
            $r->params->set('replace-existing-checks', 'true');
        }
        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * Register a service within Consul
     *
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration $service
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ServiceRegister(AgentServiceRegistration $service): ?Error
    {
        return $this->ServiceRegisterOpts($service, new ServiceRegisterOpts(['ReplaceExistingChecks' => false]));
    }

    /**
     * Remove a service from Consul
     *
     * @param string $serviceID
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ServiceDeregister(string $serviceID): ?Error
    {
        $r = new Request('PUT', sprintf('v1/agent/service/deregister/%s', $serviceID), $this->config);
        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * Set ttl check status to passing with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @deprecated use UpdateTTL
     */
    public function PassTTL(string $checkID, string $note): ?Error
    {
        return $this->UpdateTTL($checkID, $note, 'pass');
    }

    /**
     * Set ttl check status to warning with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @deprecated use UpdateTTL
     */
    public function WarnTTL(string $checkID, string $note): ?Error
    {
        return $this->UpdateTTL($checkID, $note, 'warn');
    }

    /**
     * Set ttl check status to critical with optional note
     *
     * @param string $checkID
     * @param string $note
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @deprecated use UpdateTTL
     */
    public function FailTTL(string $checkID, string $note): ?Error
    {
        return $this->UpdateTTL($checkID, $note, 'fail');
    }

    /**
     * @param string $checkID
     * @param string $output
     * @param string $status
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function UpdateTTL(string $checkID, string $output, string $status): ?Error
    {
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
                return new Error("\"{$status}\" is not a valid status.  Allowed: [\"pass\", \"warn\", \"fail\"]");
        }

        $r = new Request(
            'PUT',
            sprintf('v1/agent/check/update/%s', $checkID),
            $this->config,
            new AgentCheckUpdate(['Output' => $output, 'Status' => $status])
        );

        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration $agentCheckRegistration
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function CheckRegister(AgentCheckRegistration $agentCheckRegistration): ?Error
    {
        $r = new Request('PUT', 'v1/agent/check/register', $this->config, $agentCheckRegistration);

        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @param string $checkID
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function CheckDeregister(string $checkID): ?Error
    {
        $r = new Request('PUT', sprintf('v1/agent/check/deregister/%s', $checkID), $this->config);

        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @param string $addr
     * @param bool $wan
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Join(string $addr, bool $wan = false): ?Error
    {
        $r = new Request('PUT', sprintf('v1/agent/join/%s', $addr), $this->config);
        if ($wan) {
            $r->params->set('wan', '1');
        }
        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Leave(): ?Error
    {
        $r = new Request('PUT', 'v1/agent/leave', $this->config);
        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @param string $node
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ForceLeave(string $node): ?Error
    {
        $r = new Request('PUT', sprintf('v1/agent/force-leave/%s', $node), $this->config);
        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @param string $node
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ForceLeavePrune(string $node): ?Error
    {
        $r = new Request('PUT', sprintf('v1/agent/force-leave/%s', $node), $this->config);
        $r->params->set('prune', '1');
        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @param string $serviceID
     * @param string $reason
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function EnableServiceMaintenance(string $serviceID, string $reason = ''): ?Error
    {
        $r = new Request('PUT', sprintf('v1/agent/service/maintenance/%s', $serviceID), $this->config);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);
        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @param string $serviceID
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function DisableServiceMaintenance(string $serviceID): ?Error
    {
        $r = new Request('PUT', sprintf('v1/agent/service/maintenance/%s', $serviceID), $this->config);
        $r->params->set('enable', 'false');
        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @param string $reason
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function EnableNodeMaintenance(string $reason = ''): ?Error
    {
        $r = new Request('PUT', 'v1/agent/maintenance', $this->config);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);
        return $this->requireOK($this->doRequest($r))->Err;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Error|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function DisableNodeMaintenance(): ?Error
    {
        $r = new Request('PUT', 'v1/agent/maintenance', $this->config);
        $r->params->set('enable', 'false');
        return $this->requireOK($this->doRequest($r))->Err;
    }
}