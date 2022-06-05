<?php

declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\MapResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\ValuedStringResponse;

/**
 * Class AgentClient
 */
class AgentClient extends AbstractClient
{
    /** @var \DCarbone\PHPConsulAPI\MapResponse|null */
    private ?MapResponse $_self = null;

    /**
     * @param bool $refresh
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\MapResponse
     */
    public function Self(bool $refresh = false): MapResponse
    {
        if (!$refresh && isset($this->_self)) {
            return $this->_self;
        }
        $resp = $this->_requireOK($this->_doGet('v1/agent/self', null));
        $ret  = new MapResponse();
        $this->_unmarshalResponse($resp, $ret);
        if (null === $ret->Err) {
            $this->_self = $ret;
        }
        return $ret;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\MapResponse
     */
    public function Host(): MapResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/agent/host', null));
        $ret  = new MapResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfoResponse
     */
    public function Metrics(): MetricsInfoResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/agent/metrics', null));
        $ret  = new MetricsInfoResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function Reload(): ?Error
    {
        return $this->_executePut('v1/agent/reload', null, null)->Err;
    }

    /**
     * @param bool $refresh
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedStringResponse
     */
    public function NodeName(bool $refresh = false): ValuedStringResponse
    {
        $self     = $this->Self($refresh);
        $ret      = new ValuedStringResponse();
        $ret->Err = $self->Err;
        if (null !== $self->Err) {
            return $ret;
        }
        if (isset($self->Map['Config'], $self->Map['Config']['NodeName'])) {
            $ret->Value = $self->Map['Config']['NodeName'];
        }
        return $ret;
    }

    /**
     * @param string $filter
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Agent\AgentChecksResponse
     */
    public function ChecksWithFilter(string $filter): AgentChecksResponse
    {
        $r = $this->_newGetRequest('v1/agent/checks', null);
        $r->filterQuery($filter);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new AgentChecksResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
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
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServicesResponse
     */
    public function ServicesWithFilter(string $filter): AgentServicesResponse
    {
        $r = $this->_newGetRequest('v1/agent/services', null);
        $r->filterQuery($filter);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new AgentServicesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
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
     * @param string $id
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Agent\AgentHealthServiceResponse
     */
    public function AgentHealthServiceByID(string $id): AgentHealthServiceResponse
    {
        $r    = $this->_prepAgentHealthServiceRequest(sprintf('v1/agent/health/service/id/%s', $id));
        $resp = $this->_requireOK($this->_do($r));

        if (null !== $resp->Err) {
            return new AgentHealthServiceResponse(Consul::HealthCritical, null, $resp->Err);
        }

        if (HTTP\StatusNotFound === $resp->Response->getStatusCode()) {
            return new AgentHealthServiceResponse(Consul::HealthCritical, null, null);
        }

        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            return new AgentHealthServiceResponse(Consul::HealthCritical, null, $dec->Err);
        }

        switch ($resp->Response->getStatusCode()) {
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

        return new AgentHealthServiceResponse($status, $dec->Decoded, null);
    }

    /**
     * @param string $service
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Agent\AgentHealthServicesResponse
     */
    public function AgentHealthServiceByName(string $service): AgentHealthServicesResponse
    {
        $r    = $this->_prepAgentHealthServiceRequest(sprintf('v1/agent/health/service/name/%s', urlencode($service)));
        $resp = $this->_requireOK($this->_do($r));

        if (null !== $resp->Err) {
            return new AgentHealthServicesResponse(Consul::HealthCritical, null, $resp->Err);
        }

        if (HTTP\StatusNotFound === $resp->Response->getStatusCode()) {
            return new AgentHealthServicesResponse(Consul::HealthCritical, null, null);
        }

        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            return new AgentHealthServicesResponse(Consul::HealthCritical, null, $dec->Err);
        }

        switch ($resp->Response->getStatusCode()) {
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

        return new AgentHealthServicesResponse($status, $dec->Decoded, null);
    }

    /**
     * @param string $serviceID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceResponse
     */
    public function Service(string $serviceID, ?QueryOptions $opts = null): AgentServiceResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/agent/service/%s', $serviceID), $opts));
        $ret  = new AgentServiceResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Agent\AgentMembersResponse
     */
    public function Members(): AgentMembersResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/agent/members', null));
        $ret  = new AgentMembersResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\MemberOpts $memberOpts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Agent\AgentMembersResponse
     */
    public function MemberOpts(MemberOpts $memberOpts): AgentMembersResponse
    {
        $r = $this->_newGetRequest('v1/agent/members', null);
        $r->params->set('segment', $memberOpts->Segment);
        if ($memberOpts->WAN) {
            $r->params->set('wan', '1');
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new AgentMembersResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration $service
     * @param \DCarbone\PHPConsulAPI\Agent\ServiceRegisterOpts $registerOpts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function ServiceRegisterOpts(AgentServiceRegistration $service, ServiceRegisterOpts $registerOpts): ?Error
    {
        $r = $this->_newPutRequest('v1/agent/service/register', $service, null);
        if ($registerOpts->ReplaceExistingChecks) {
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
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function ServiceDeregister(string $serviceID): ?Error
    {
        $r = new Request(HTTP\MethodPut, sprintf('v1/agent/service/deregister/%s', $serviceID), $this->_config, null);
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
     * @throws \Exception
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
            sprintf('v1/agent/check/update/%s', $checkID),
            $this->_config,
            new AgentCheckUpdate(['Output' => $output, 'Status' => $status])
        );

        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration $check
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function CheckRegister(AgentCheckRegistration $check): ?Error
    {
        return $this->_executePut('v1/agent/check/register', $check, null)->Err;
    }

    /**
     * @param string $checkID
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function CheckDeregister(string $checkID): ?Error
    {
        return $this->_executePut(sprintf('v1/agent/check/deregister/%s', $checkID), null, null)->Err;
    }

    /**
     * @param string $addr
     * @param bool $wan
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function Join(string $addr, bool $wan = false): ?Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/join/%s', $addr), null, null);
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
        return $this->_executePut(sprintf('v1/agent/force-leave/%s', $node), null, null)->Err;
    }

    /**
     * @param string $node
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function ForceLeavePrune(string $node): ?Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/force-leave/%s', $node), null, null);
        $r->params->set('prune', '1');
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param string $serviceID
     * @param string $reason
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function EnableServiceMaintenance(string $serviceID, string $reason = ''): ?Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/service/maintenance/%s', $serviceID), null, null);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param string $serviceID
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function DisableServiceMaintenance(string $serviceID): ?Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/service/maintenance/%s', $serviceID), null, null);
        $r->params->set('enable', 'false');
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param string $reason
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
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
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function DisableNodeMaintenance(): ?Error
    {
        $r = $this->_newPutRequest('v1/agent/maintenance', null, null);
        $r->params->set('enable', 'false');
        return $this->_requireOK($this->_do($r))->Err;
    }

    protected function _prepAgentHealthServiceRequest(string $path): Request
    {
        $r = $this->_newGetRequest($path, null);
        $r->params->add('format', 'json');
        $r->header->set('Accept', 'application/json');
        return $r;
    }
}
