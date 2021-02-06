<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\Go\HTTP;
use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\ValuedStringResponse;

/**
 * Class AgentClient
 */
class AgentClient extends AbstractClient
{
    /** @var array|null */
    private ?array $_self = null;

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\AgentSelfResponse
     */
    public function Self(): AgentSelfResponse
    {
        $r = $this->_newGetRequest('v1/agent/self', null);

        $resp = $this->_requireOK($this->_do($r));
        if (null !== $resp->Err) {
            return new AgentSelfResponse(null, null, $resp->Err);
        }

        $qm = $this->buildQueryMeta($resp->Duration, $resp->Response, $r->getUri());

        $decoded = $this->decodeBody($resp->Response->getBody());

        if (null !== $decoded->Err) {
            return new AgentSelfResponse(null, $qm, $decoded->Err);
        }

        $this->_self = $decoded->Decoded;

        return new AgentSelfResponse($decoded->Decoded, $qm, null);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfoResponse
     */
    public function Metrics(): MetricsInfoResponse
    {
        $r = $this->_newGetRequest('v1/agent/metrics', null);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function Reload(): ?Error
    {
        $r = new Request(HTTP\MethodPut, 'v1/agent/reload', $this->config, null);

        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedStringResponse
     */
    public function NodeName(): ValuedStringResponse
    {
        if (null === $this->_self) {
            [$_, $_, $err] = $this->Self();
            if (null !== $err) {
                return new ValuedStringResponse('', $err);
            }
        }

        if (isset($this->_self['Config'], $this->_self['Config']['NodeName'])) {
            return new ValuedStringResponse($this->_self['Config']['NodeName'], null);
        }

        return new ValuedStringResponse('', null);
    }

    /**
     * @param string $filter
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\AgentChecksResponse
     */
    public function ChecksWithFilter(string $filter): AgentChecksResponse
    {
        $r = new Request(HTTP\MethodGet, 'v1/agent/checks', $this->config, null);
        $r->filterQuery($filter);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));

        if (null !== $err) {
            return new AgentChecksResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        return new AgentChecksResponse($data, $err);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\AgentChecksResponse
     */
    public function Checks(): AgentChecksResponse
    {
        return $this->checksWithFilter('');
    }

    /**
     * @param string $filter
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServicesResponse
     */
    public function ServicesWithFilter(string $filter): AgentServicesResponse
    {
        $r = new Request(HTTP\MethodGet, 'v1/agent/services', $this->config, null);
        $r->filterQuery($filter);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));

        if (null !== $err) {
            return new AgentServicesResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        return new AgentServicesResponse($data, $err);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServicesResponse
     */
    public function Services(): AgentServicesResponse
    {
        return $this->ServicesWithFilter('');
    }

    /**
     * @param string $service
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\AgentHealthServiceResponse
     */
    public function AgentHealthServiceByName(string $service): AgentHealthServiceResponse
    {
        $r = $this->_newGetRequest(\sprintf('v1/agent/health/service/name/%s', \urlencode($service)), null);
        $r->params->add('format', 'json');
        $r->header->set('Accept', 'application/json');

        $res = $this->_do($r);
        if (null !== $res->Err) {
            return new AgentHealthServiceResponse(Consul::HealthCritical, null, $res->Err);
        }

        if (HTTP\StatusNotFound === $res->Response->getStatusCode()) {
            return new AgentHealthServiceResponse(Consul::HealthCritical, null, null);
        }

        [$data, $err] = $this->decodeBody($res->Response->getBody());
        if (null !== $err) {
            return new AgentHealthServiceResponse(Consul::HealthCritical, null, $res->Err);
        }

        switch ($res->Response->getStatusCode()) {
            case HTTP\StatusOK:
                $status = Consul::HealthPassing;
                break;
            case HTTP\StatusTooManyRequests:
                $status = Consul::HealthWarning;
                break;
            case HTTP\StatusServiceUnavailable:
                $status = Consul::HealthCritical;
                break;

            default:
                $status = Consul::HealthCritical;
        }

        return new AgentHealthServiceResponse($status, $data, null);
    }

    /**
     * @param string $id
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\AgentHealthServiceResponse
     */
    public function AgentHealthServiceByID(string $id): AgentHealthServiceResponse
    {
        $r = $this->_newGetRequest(\sprintf('v1/agent/health/service/id/%s', $id), null);
        $r->params->add('format', 'json');
        $r->header->set('Accept', 'application/json');

        $res = $this->_do($r);
        if (null !== $res->Err) {
            return new AgentHealthServiceResponse(Consul::HealthCritical, null, $res->Err);
        }

        if (HTTP\StatusNotFound === $res->Response->getStatusCode()) {
            return new AgentHealthServiceResponse(Consul::HealthCritical, null, null);
        }

        [$data, $err] = $this->decodeBody($res->Response->getBody());
        if (null !== $err) {
            return new AgentHealthServiceResponse(Consul::HealthCritical, null, $res->Err);
        }

        switch ($res->Response->getStatusCode()) {
            case HTTP\StatusOK:
                $status = Consul::HealthPassing;
                break;
            case HTTP\StatusTooManyRequests:
                $status = Consul::HealthWarning;
                break;
            case HTTP\StatusServiceUnavailable:
                $status = Consul::HealthCritical;
                break;

            default:
                $status = Consul::HealthCritical;
        }

        return new AgentHealthServiceResponse($status, $data, null);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\AgentMembersResponse
     */
    public function Members(): AgentMembersResponse
    {
        $r = new Request(HTTP\MethodGet, 'v1/agent/members', $this->config, null);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new AgentMembersResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        return new AgentMembersResponse($data, $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\MemberOpts $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Agent\AgentMembersResponse
     */
    public function MemberOpts(MemberOpts $opts): AgentMembersResponse
    {
        $r = new Request(HTTP\MethodGet, 'v1/agent/members', $this->config, null);
        $r->params->set('segment', $opts->Segment);
        if ($opts->WAN) {
            $r->params->set('wan', '1');
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new AgentMembersResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        return new AgentMembersResponse($data, $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration $service
     * @param \DCarbone\PHPConsulAPI\Agent\ServiceRegisterOpts $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function ServiceRegisterOpts(AgentServiceRegistration $service, ServiceRegisterOpts $opts): ?Error
    {
        $r = new Request(HTTP\MethodPut, 'v1/agent/service/register', $this->config, $service);
        if ($opts->ReplaceExistingChecks) {
            $r->params->set('replace-existing-checks', 'true');
        }
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * Register a service within Consul
     *
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration $service
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function ServiceRegister(AgentServiceRegistration $service): ?Error
    {
        return $this->ServiceRegisterOpts($service, new ServiceRegisterOpts(['ReplaceExistingChecks' => false]));
    }

    /**
     * Remove a service from Consul
     *
     * @param string $serviceID
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function ServiceDeregister(string $serviceID): ?Error
    {
        $r = new Request(HTTP\MethodPut, \sprintf('v1/agent/service/deregister/%s', $serviceID), $this->config, null);
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * Set ttl check status to passing with optional note
     *
     * @param string $checkID
     * @param string $note
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
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
            HTTP\MethodPut,
            \sprintf('v1/agent/check/update/%s', $checkID),
            $this->config,
            new AgentCheckUpdate(['Output' => $output, 'Status' => $status])
        );

        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration $agentCheckRegistration
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function CheckRegister(AgentCheckRegistration $agentCheckRegistration): ?Error
    {
        $r = new Request(HTTP\MethodPut, 'v1/agent/check/register', $this->config, $agentCheckRegistration);

        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param string $checkID
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function CheckDeregister(string $checkID): ?Error
    {
        return $this->_executePut(\sprintf('v1/agent/check/deregister/%s', $checkID), null, null)->Err;
    }

    /**
     * @param string $addr
     * @param bool $wan
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function Join(string $addr, bool $wan = false): ?Error
    {
        $r = $this->_newPutRequest(\sprintf('v1/agent/join/%s', $addr), null, null);
        if ($wan) {
            $r->params->set('wan', '1');
        }
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function Leave(): ?Error
    {
        return $this->_executePut('v1/agent/leave', null, null)->Err;
    }

    /**
     * @param string $node
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function ForceLeave(string $node): ?Error
    {
        return $this->_executePut(\sprintf('v1/agent/force-leave/%s', $node), null, null)->Err;
    }

    /**
     * @param string $node
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function ForceLeavePrune(string $node): ?Error
    {
        $r = $this->_newPutRequest(\sprintf('v1/agent/force-leave/%s', $node), null, null);
        $r->params->set('prune', '1');
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param string $serviceID
     * @param string $reason
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function EnableServiceMaintenance(string $serviceID, string $reason = ''): ?Error
    {
        $r = $this->_newPutRequest(\sprintf('v1/agent/service/maintenance/%s', $serviceID), null, null);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param string $serviceID
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function DisableServiceMaintenance(string $serviceID): ?Error
    {
        $r = $this->_newPutRequest(\sprintf('v1/agent/service/maintenance/%s', $serviceID), null, null);
        $r->params->set('enable', 'false');
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param string $reason
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function EnableNodeMaintenance(string $reason = ''): ?Error
    {
        $r = $this->_newPutRequest('v1/agent/maintenance', null, null);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function DisableNodeMaintenance(): ?Error
    {
        $r = $this->_newPutRequest('v1/agent/maintenance', null, null);
        $r->params->set('enable', 'false');
        return $this->_requireOK($this->_do($r))->Err;
    }
}
