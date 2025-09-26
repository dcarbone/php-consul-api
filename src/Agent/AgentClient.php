<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

class AgentClient extends AbstractClient
{
    private null|MapResponse $_self = null;

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

    public function Host(): MapResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/agent/host', null));
        $ret  = new MapResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Metrics(): MetricsInfoResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/agent/metrics', null));
        $ret  = new MetricsInfoResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Reload(): null|Error
    {
        return $this->_executePut('v1/agent/reload', null, null)->Err;
    }

    public function NodeName(bool $refresh = false): ValuedStringResponse
    {
        $self     = $this->Self($refresh);
        $ret      = new ValuedStringResponse();
        $ret->Err = $self->Err;
        if (null !== $self->Err) {
            return $ret;
        }
        if (isset($self->Map->Config, $self->Map->Config->NodeName)) {
            $ret->Value = $self->Map->Config->NodeName;
        }
        return $ret;
    }

    public function ChecksWithFilter(string $filter): AgentChecksResponse
    {
        $r = $this->_newGetRequest('v1/agent/checks', null);
        $r->filterQuery($filter);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new AgentChecksResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Checks(): AgentChecksResponse
    {
        return $this->checksWithFilter('');
    }

    public function ServicesWithFilter(string $filter): AgentServicesResponse
    {
        $r = $this->_newGetRequest('v1/agent/services', null);
        $r->filterQuery($filter);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new AgentServicesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Services(): AgentServicesResponse
    {
        return $this->ServicesWithFilter('');
    }

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

        $status = match ($resp->Response->getStatusCode()) {
            HTTP\StatusOK => Consul::HealthPassing,
            HTTP\StatusTooManyRequests => Consul::HealthWarning,
            HTTP\StatusServiceUnavailable => Consul::HealthCritical,
            default => Consul::HealthCritical,
        };

        return new AgentHealthServiceResponse($status, $dec->Decoded, null);
    }

    public function AgentHealthServiceByName(string $service): AgentHealthServicesResponse
    {
        $r    = $this->_prepAgentHealthServiceRequest(sprintf('v1/agent/health/service/name/%s', urlencode($service)));
        $resp = $this->_requireOK($this->_do($r));

        if (null !== $resp->Err) {
            return new AgentHealthServicesResponse(Consul::HealthCritical, [], $resp->Err);
        }

        if (HTTP\StatusNotFound === $resp->Response->getStatusCode()) {
            return new AgentHealthServicesResponse(Consul::HealthCritical, [], null);
        }

        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            return new AgentHealthServicesResponse(Consul::HealthCritical, [], $dec->Err);
        }

        $status = match ($resp->Response->getStatusCode()) {
            HTTP\StatusOK => Consul::HealthPassing,
            HTTP\StatusTooManyRequests => Consul::HealthWarning,
            default => Consul::HealthCritical,
        };

        return new AgentHealthServicesResponse($status, $dec->Decoded, null);
    }

    public function Service(string $serviceID, null|QueryOptions $opts = null): AgentServiceResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/agent/service/%s', $serviceID), $opts));
        $ret  = new AgentServiceResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Members(): AgentMembersResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/agent/members', null));
        $ret  = new AgentMembersResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

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

    public function ServiceRegisterOpts(AgentServiceRegistration $service, ServiceRegisterOpts $registerOpts): null|Error
    {
        $r = $this->_newPutRequest('v1/agent/service/register', $service, null);
        if ($registerOpts->ReplaceExistingChecks) {
            $r->params->set('replace-existing-checks', 'true');
        }
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function ServiceRegister(AgentServiceRegistration $service): null|Error
    {
        return $this->ServiceRegisterOpts($service, new ServiceRegisterOpts(ReplaceExistingChecks: false));
    }

    public function ServiceDeregister(string $serviceID): null|Error
    {
        $r = new Request(HTTP\MethodPut, sprintf('v1/agent/service/deregister/%s', $serviceID), $this->_config, null);
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function PassTTL(string $checkID, string $note): null|Error
    {
        return $this->UpdateTTL($checkID, $note, 'pass');
    }

    public function WarnTTL(string $checkID, string $note): null|Error
    {
        return $this->UpdateTTL($checkID, $note, 'warn');
    }

    public function FailTTL(string $checkID, string $note): null|Error
    {
        return $this->UpdateTTL($checkID, $note, 'fail');
    }

    public function UpdateTTL(string $checkID, string $output, string $status): null|Error
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
            new AgentCheckUpdate(Status: $status, Output: $output)
        );

        return $this->_requireOK($this->_do($r))->Err;
    }

    public function CheckRegister(AgentCheckRegistration $check): null|Error
    {
        return $this->_executePut('v1/agent/check/register', $check, null)->Err;
    }

    public function CheckDeregister(string $checkID): null|Error
    {
        return $this->_executePut(sprintf('v1/agent/check/deregister/%s', $checkID), null, null)->Err;
    }

    public function Join(string $addr, bool $wan = false): null|Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/join/%s', $addr), null, null);
        if ($wan) {
            $r->params->set('wan', '1');
        }
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function Leave(): null|Error
    {
        return $this->_executePut('v1/agent/leave', null, null)->Err;
    }

    public function ForceLeave(string $node): null|Error
    {
        return $this->_executePut(sprintf('v1/agent/force-leave/%s', $node), null, null)->Err;
    }

    public function ForceLeavePrune(string $node): null|Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/force-leave/%s', $node), null, null);
        $r->params->set('prune', '1');
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function EnableServiceMaintenance(string $serviceID, string $reason = ''): null|Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/service/maintenance/%s', $serviceID), null, null);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function DisableServiceMaintenance(string $serviceID): null|Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/service/maintenance/%s', $serviceID), null, null);
        $r->params->set('enable', 'false');
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function EnableNodeMaintenance(string $reason = ''): null|Error
    {
        $r = $this->_newPutRequest('v1/agent/maintenance', null, null);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function DisableNodeMaintenance(): null|Error
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
