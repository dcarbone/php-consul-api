<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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
use DCarbone\PHPConsulAPI\PHPLib\AbstractClient;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPI\PHPLib\MapResponse;
use DCarbone\PHPConsulAPI\PHPLib\RequestResponse;
use DCarbone\PHPConsulAPI\PHPLib\ValuedBoolResponse;
use DCarbone\PHPConsulAPI\PHPLib\ValuedQueryStringsResponse;
use DCarbone\PHPConsulAPI\PHPLib\ValuedStringResponse;
use DCarbone\PHPConsulAPI\PHPLib\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\PHPLib\WriteResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\WriteOptions;

class OperatorClient extends AbstractClient
{
    public function AreaCreate(Area $area, null|WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_writeIDResponse($this->_requireOK($this->_doPost('v1/operator/area', $area, $opts)));
    }

    public function AreaUpdate(string $areaID, Area $area, null|WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_writeIDResponse(
            $this->_requireOK($this->_doPut(sprintf('v1/operator/area/%s', $areaID), $area, $opts))
        );
    }

    public function AreaGet(string $areaID, null|QueryOptions $opts = null): OperatorAreasResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/operator/area/%s', urlencode($areaID)), $opts));
        $ret  = new OperatorAreasResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AreaList(null|QueryOptions $opts = null): OperatorAreasResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/area', $opts));
        $ret  = new OperatorAreasResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AreaDelete(string $areaID, null|WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(sprintf('v1/operator/area/%s', $areaID), $opts);
    }

    public function AreaJoin(string $areaID, null|WriteOptions $opts = null, string ...$addresses): OperatorAreaJoinResponse
    {
        $resp = $this->_requireOK($this->_doPut(sprintf('v1/operator/area/%s/join', $areaID), $addresses, $opts));
        $ret  = new OperatorAreaJoinResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AreaMembers(string $areaID, null|QueryOptions $opts = null): OperatorSerfMembersResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/operator/area/%s/members', $areaID), $opts));
        $ret  = new OperatorSerfMembersResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AutopilotGetConfiguration(null|QueryOptions $opts = null): OperatorAutopilotConfigurationResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/autopilot/configuration', $opts));
        $ret  = new OperatorAutopilotConfigurationResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AutopilotSetConfiguration(AutopilotConfiguration $conf, null|WriteOptions $opts = null): null|Error
    {
        return $this->_requireOK($this->_doPut('v1/operator/autopilot/configuration', $conf, $opts))->Err;
    }

    public function AutopilotCASConfiguration(
        AutopilotConfiguration $conf,
        null|WriteOptions $opts = null
    ): ValuedBoolResponse {
        $r = $this->_newPutRequest('v1/operator/autopilot/configuration', $conf, $opts);
        $r->params->set('cas', (string)$conf->ModifyIndex);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ValuedBoolResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AutopilotServerHealth(null|QueryOptions $opts = null): OperatorHealthReplyResponse
    {
        $resp = $this->_requireStatus(
            $this->_doGet('v1/operator/autopilot/health', $opts),
            HTTP\StatusOK,
            HTTP\StatusTooManyRequests
        );
        $ret  = new OperatorHealthReplyResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AutopilotState(null|QueryOptions $opts = null): AutopilotStateResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/autopilot/state', $opts));
        $ret  = new AutopilotStateResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function RaftGetConfiguration(null|QueryOptions $opts = null): OperatorRaftConfigurationResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/raft/configuration', $opts));
        $ret  = new OperatorRaftConfigurationResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function RaftRemovePeerByAddress(string $address, null|WriteOptions $opts = null): null|Error
    {
        $r = $this->_newDeleteRequest('v1/operator/raft/peer', $opts);
        $r->applyOptions($opts);
        $r->params->set('address', $address);
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function RaftLeaderTransfer(string $id = '', null|QueryOptions $opts = null): ValuedBoolResponse
    {
        $r = $this->_newPostRequest('v1/operator/raft/transfer-leader', null, $opts);
        if ('' !== $id) {
            $r->params->set('id', $id);
        }
        return $this->_executeBooleanFieldResponse($this->_requireOK($this->_do($r)), 'Success');
    }

    public function RaftRemovePeerByID(string $id, null|WriteOptions $opts = null): null|Error
    {
        $r = $this->_newDeleteRequest('v1/operator/raft/peer', $opts);
        $r->applyOptions($opts);
        $r->params->set('id', $id);
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function KeyringInstall(string $key, null|WriteOptions $opts = null): null|Error
    {
        return $this->_requireOK($this->_doPost('v1/operator/keyring', ['Key' => $key], $opts))->Err;
    }

    public function KeyringList(null|QueryOptions $opts = null): OperatorKeyringListResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/keyring', $opts));
        $ret  = new OperatorKeyringListResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function KeyringRemove(string $key, null|WriteOptions $opts = null): null|Error
    {
        $r = $this->_newRequest('DELETE', 'v1/operator/keyring', ['Key' => $key], $opts);
        return $this->_requireOK($this->_do($r))->Err;
    }

    public function KeyringUse(string $key, null|WriteOptions $opts = null): null|Error
    {
        return $this->_requireOK($this->_doPut('v1/operator/keyring', ['Key' => $key], $opts))->Err;
    }

    public function SegmentList(null|QueryOptions $opts = null): ValuedQueryStringsResponse
    {
        return $this->_executeGetValuedStrs('v1/operator/segment', $opts);
    }

    public function Usage(null|QueryOptions $opts = null): OperatorUsageResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/usage', $opts));
        $ret  = new OperatorUsageResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AuditHash(string $input, null|QueryOptions $opts = null): ValuedStringResponse
    {
        $r = $this->_newPostRequest('v1/operator/audit-hash', ['Input' => $input], $opts);
        return $this->_executeStringFieldResponse($this->_requireOK($this->_do($r)), 'Hash');
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PHPLib\MapResponse<mixed>
     */
    public function LicenseGet(null|QueryOptions $opts = null): MapResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/license', $opts));
        $ret  = new MapResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function LicenseGetSigned(null|QueryOptions $opts = null): ValuedStringResponse
    {
        $ret = new ValuedStringResponse();
        $r = $this->_newGetRequest('v1/operator/license', $opts);
        $r->params->set('signed', '1');
        $resp = $this->_requireOK($this->_do($r));
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }
        $ret->Value = (string)$resp->Response->getBody();
        return $ret;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PHPLib\MapResponse<mixed>
     */
    public function LicenseReset(null|WriteOptions $opts = null): MapResponse
    {
        $resp = $this->_requireOK($this->_doDelete('v1/operator/license', $opts));
        $ret  = new MapResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PHPLib\MapResponse<mixed>
     */
    public function LicensePut(string $license, null|WriteOptions $opts = null): MapResponse
    {
        $resp = $this->_requireOK($this->_doPut('v1/operator/license', $license, $opts));
        $ret  = new MapResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
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

        $decoded = $dec->Decoded;
        if (is_array($decoded)) {
            $ret->Value = $decoded['ID'] ?? '';
        } elseif ($decoded instanceof \stdClass) {
            $ret->Value = isset($decoded->ID) ? (string)$decoded->ID : '';
        } else {
            $ret->Value = '';
        }
        return $ret;
    }

    private function _executeBooleanFieldResponse(RequestResponse $resp, string $field): ValuedBoolResponse
    {
        $ret = new ValuedBoolResponse();
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }
        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            $ret->Err = $dec->Err;
            return $ret;
        }
        $decoded = $dec->Decoded;
        if (is_array($decoded) && array_key_exists($field, $decoded)) {
            $ret->Value = (bool)$decoded[$field];
            return $ret;
        }
        if ($decoded instanceof \stdClass && isset($decoded->{$field})) {
            $ret->Value = (bool)$decoded->{$field};
            return $ret;
        }
        $ret->Value = false;
        return $ret;
    }

    private function _executeStringFieldResponse(RequestResponse $resp, string $field): ValuedStringResponse
    {
        $ret = new ValuedStringResponse();
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }
        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            $ret->Err = $dec->Err;
            return $ret;
        }
        $decoded = $dec->Decoded;
        if (is_array($decoded) && array_key_exists($field, $decoded)) {
            $ret->Value = (string)$decoded[$field];
            return $ret;
        }
        if ($decoded instanceof \stdClass && isset($decoded->{$field})) {
            $ret->Value = (string)$decoded->{$field};
            return $ret;
        }
        $ret->Value = '';
        return $ret;
    }
}
