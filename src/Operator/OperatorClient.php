<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\RequestResponse;
use DCarbone\PHPConsulAPI\ValuedBoolResponse;
use DCarbone\PHPConsulAPI\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

class OperatorClient extends AbstractClient
{
    public function AreaCreate(Area $area, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_writeIDResponse($this->_requireOK($this->_doPost('v1/operator/area', $area, $opts)));
    }

    public function AreaUpdate(string $areaID, Area $area, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_writeIDResponse(
            $this->_requireOK($this->_doPut(sprintf('v1/operator/area/%s', $areaID), $area, $opts))
        );
    }

    public function AreaGet(string $areaID, ?QueryOptions $opts = null): OperatorAreasResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/operator/area/%s', urlencode($areaID)), $opts));
        $ret  = new OperatorAreasResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AreaList(?QueryOptions $opts = null): OperatorAreasResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/area', $opts));
        $ret  = new OperatorAreasResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AreaDelete(string $areaID, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(sprintf('v1/operator/area/%s', $areaID), $opts);
    }

    public function AreaJoin(string $areaID, array $addresses, ?WriteOptions $opts = null): OperatorAreaJoinResponse
    {
        $resp = $this->_requireOK($this->_doPut(sprintf('v1/operator/area/%s/join', $areaID), $addresses, $opts));
        $ret  = new OperatorAreaJoinResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AreaMembers(string $areaID, ?QueryOptions $opts = null): OperatorSerfMembersResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/operator/area/%s/members', $areaID), $opts));
        $ret  = new OperatorSerfMembersResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AutopilotGetConfiguration(?QueryOptions $opts = null): OperatorAutopilotConfigurationResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/autopilot/configuration', $opts));
        $ret  = new OperatorAutopilotConfigurationResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AutopilotSetConfiguration(AutopilotConfiguration $conf, ?WriteOptions $opts = null): ?Error
    {
        return $this->_requireOK($this->_doPut('v1/operator/autopilot/configuration', $conf, $opts))->Err;
    }

    public function AutopilotCASConfiguration(
        AutopilotConfiguration $conf,
        ?WriteOptions $opts = null
    ): ValuedBoolResponse {
        $resp = $this->_requireOK($this->_doPut('v1/operator/autopilot/configuration', $conf, $opts));
        $ret  = new ValuedBoolResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AutopilotServerHealth(?QueryOptions $opts = null): OperatorHealthReplyResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/autopilot/health', $opts));
        $ret  = new OperatorHealthReplyResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AutopilotState(?QueryOptions $opts = null): AutopilotStateResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/autopilot/state', $opts));
        $ret  = new AutopilotStateResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function RaftGetConfiguration(?QueryOptions $opts = null): OperatorRaftConfigurationResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/raft/configuration', $opts));
        $ret  = new OperatorRaftConfigurationResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function RaftRemovePeerByAddress(string $address, ?WriteOptions $opts = null): ?Error
    {
        $r = $this->_newDeleteRequest('v1/operator/raft/peer', $opts);
        $r->applyOptions($opts);
        $r->params->set('address', $address);
        return $this->_requireOK($this->_do($r))->Err;
    }

    protected function _writeIDResponse(RequestResponse $resp): ValuedWriteStringResponse
    {
        $ret = new ValuedWriteStringResponse();

        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }

        $ret->WriteMeta = $resp->buildWriteMeta();

        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            $ret->Err = $dec->Err;
            return $ret;
        }

        $ret->Value = $dec->Decoded['ID'] ?? '';
        return $ret;
    }
}
