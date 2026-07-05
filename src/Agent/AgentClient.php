<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\PHPLib\AbstractClient;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPI\PHPLib\MapResponse;
use DCarbone\PHPConsulAPI\PHPLib\Request;
use DCarbone\PHPConsulAPI\PHPLib\ValuedStringResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\WriteOptions;

class AgentClient extends AbstractClient
{
    /** @var \DCarbone\PHPConsulAPI\PHPLib\MapResponse<mixed>|null */
    private null|MapResponse $_self = null;

    /**
     * @param bool $refresh
     * @return \DCarbone\PHPConsulAPI\PHPLib\MapResponse<mixed>
     * @throws \Exception
     */
    public function Self(bool $refresh = false): MapResponse
    {
        if (!$refresh && null !== $this->_self) {
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
     * @return \DCarbone\PHPConsulAPI\PHPLib\MapResponse<mixed>
     * @throws \Exception
     */
    public function Host(): MapResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/agent/host', null));
        $ret  = new MapResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PHPLib\MapResponse<mixed>
     * @throws \Exception
     */
    public function Version(): MapResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/agent/version', null));
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
        if (isset($self->Map['Config']->NodeName)) {
            $ret->Value = $self->Map['Config']->NodeName;
        }
        return $ret;
    }

    public function Checks(string $filter = '', null|QueryOptions $opts = null): AgentChecksResponse
    {
        $r = $this->_newGetRequest('v1/agent/checks', $opts);
        if ('' !== $filter) {
            $r->filterQuery($filter);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new AgentChecksResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Services(string $filter = '', null|QueryOptions $opts = null): AgentServicesResponse
    {
        $r = $this->_newGetRequest('v1/agent/services', $opts);
        if ('' !== $filter) {
            $r->filterQuery($filter);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new AgentServicesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AgentHealthServiceByID(string $id, null|QueryOptions $opts = null): AgentHealthServiceResponse
    {
        $r    = $this->_prepAgentHealthServiceRequest(sprintf('v1/agent/health/service/id/%s', $id), $opts);
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

    public function AgentHealthServiceByName(string $service, null|QueryOptions $opts = null): AgentHealthServicesResponse
    {
        $r    = $this->_prepAgentHealthServiceRequest(sprintf('v1/agent/health/service/name/%s', urlencode($service)), $opts);
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

    public function MembersOpts(MembersOpts $memberOpts): AgentMembersResponse
    {
        $r = $this->_newGetRequest('v1/agent/members', null);
        $r->params->set('segment', $memberOpts->Segment);
        if ($memberOpts->WAN) {
            $r->params->set('wan', '1');
        }
        $r->filterQuery($memberOpts->Filter);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new AgentMembersResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function MemberOpts(MembersOpts $memberOpts): AgentMembersResponse
    {
        return $this->MembersOpts($memberOpts);
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

    public function ServiceDeregister(string $serviceID, null|QueryOptions $opts = null): null|Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/service/deregister/%s', $serviceID), null, $opts);
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

    public function UpdateTTL(string $checkID, string $output, string $status, null|QueryOptions $opts = null): null|Error
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

        $r = $this->_newPutRequest(
            sprintf('v1/agent/check/update/%s', $checkID),
            new AgentCheckUpdate(Status: $status, Output: $output),
            $opts,
        );

        return $this->_requireOK($this->_do($r))->Err;
    }

    public function CheckRegister(AgentCheckRegistration $check, null|QueryOptions $opts = null): null|Error
    {
        $r = $this->_newPutRequest('v1/agent/check/register', $check, $opts);
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function CheckDeregister(string $checkID, null|QueryOptions $opts = null): null|Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/check/deregister/%s', $checkID), null, $opts);
        return $this->_requireOK($this->_do($r))->Err;
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

    public function ForceLeave(string $node, null|ForceLeaveOpts $opts = null, null|QueryOptions $qOpts = null): null|Error
    {
        $forceLeaveOpts = $opts ?? new ForceLeaveOpts();
        $r = $this->_newPutRequest(sprintf('v1/agent/force-leave/%s', $node), null, $qOpts);
        if ($forceLeaveOpts->Prune) {
            $r->params->set('prune', '1');
        }
        if ($forceLeaveOpts->WAN) {
            $r->params->set('wan', '1');
        }
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function ForceLeavePrune(string $node): null|Error
    {
        return $this->ForceLeave($node, new ForceLeaveOpts(Prune: true));
    }

    public function ConnectAuthorize(AgentAuthorizeParams $auth): AgentAuthorizeResponse
    {
        $resp = $this->_requireOK($this->_do($this->_newPostRequest('v1/agent/connect/authorize', $auth, null)));
        $ret  = new AgentAuthorizeResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function ConnectCARoots(null|QueryOptions $opts = null): ConnectCARootsResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/agent/connect/ca/roots', $opts));
        $ret  = new ConnectCARootsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function ConnectCALeaf(string $serviceID, null|QueryOptions $opts = null): ConnectCALeafResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/agent/connect/ca/leaf/%s', $serviceID), $opts));
        $ret  = new ConnectCALeafResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function EnableServiceMaintenance(string $serviceID, string $reason = '', null|QueryOptions $opts = null): null|Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/service/maintenance/%s', $serviceID), null, $opts);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function DisableServiceMaintenance(string $serviceID, null|QueryOptions $opts = null): null|Error
    {
        $r = $this->_newPutRequest(sprintf('v1/agent/service/maintenance/%s', $serviceID), null, $opts);
        $r->params->set('enable', 'false');
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function EnableNodeMaintenance(string $reason = '', null|QueryOptions $opts = null): null|Error
    {
        $r = $this->_newPutRequest('v1/agent/maintenance', null, $opts);
        $r->params->set('enable', 'true');
        $r->params->set('reason', $reason);
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function DisableNodeMaintenance(null|QueryOptions $opts = null): null|Error
    {
        $r = $this->_newPutRequest('v1/agent/maintenance', null, $opts);
        $r->params->set('enable', 'false');
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function UpdateACLToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('acl_token', $token, $opts);
    }

    public function UpdateACLAgentToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('acl_agent_token', $token, $opts);
    }

    public function UpdateACLAgentMasterToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('acl_agent_master_token', $token, $opts);
    }

    public function UpdateACLReplicationToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('acl_replication_token', $token, $opts);
    }

    public function UpdateDefaultACLToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('default', $token, $opts);
    }

    public function UpdateAgentACLToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('agent', $token, $opts);
    }

    public function UpdateAgentRecoveryACLToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('agent_recovery', $token, $opts);
    }

    public function UpdateAgentMasterACLToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('agent_master', $token, $opts);
    }

    public function UpdateReplicationACLToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('replication', $token, $opts);
    }

    public function UpdateConfigFileRegistrationToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('config_file_service_registration', $token, $opts);
    }

    public function UpdateDNSToken(string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->updateToken('dns', $token, $opts);
    }

    protected function updateToken(string $target, string $token, null|WriteOptions $opts = null): null|Error
    {
        return $this->_executePut(
            sprintf('v1/agent/token/%s', $target),
            new AgentToken(Token: $token),
            $opts,
        )->Err;
    }

    protected function _prepAgentHealthServiceRequest(string $path, null|QueryOptions $opts = null): Request
    {
        $r = $this->_newGetRequest($path, $opts);
        $r->params->add('format', 'json');
        $r->header->set('Accept', 'application/json');
        return $r;
    }
}
