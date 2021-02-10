<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\RequestResponse;
use DCarbone\PHPConsulAPI\ValuedBoolResponse;
use DCarbone\PHPConsulAPI\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class OperatorClient
 */
class OperatorClient extends AbstractClient
{
    /**
     * @param \DCarbone\PHPConsulAPI\Operator\Area $area
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    public function AreaCreate(Area $area, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_writeIDResponse($this->_requireOK($this->_doPost('v1/operator/area', $area, $opts)));
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\Operator\Area $area
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    public function AreaUpdate(string $areaID, Area $area, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_writeIDResponse(
            $this->_requireOK($this->_doPut(\sprintf('v1/operator/area/%s', $areaID), $area, $opts))
        );
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAreasResponse
     */
    public function AreaGet(string $areaID, ?QueryOptions $opts = null): OperatorAreasResponse
    {
        $resp = $this->_requireOK($this->_doGet(\sprintf('v1/operator/area/%s', \urlencode($areaID)), $opts));
        $ret  = new OperatorAreasResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAreasResponse
     */
    public function AreaList(?QueryOptions $opts = null): OperatorAreasResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/area', $opts));
        $ret  = new OperatorAreasResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function AreaDelete(string $areaID, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(\sprintf('v1/operator/area/%s', $areaID), $opts);
    }

    /**
     * @param string $areaID
     * @param array $addresses
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAreaJoinResponse
     */
    public function AreaJoin(string $areaID, array $addresses, ?WriteOptions $opts = null): OperatorAreaJoinResponse
    {
        $resp = $this->_requireOK($this->_doPut(\sprintf('v1/operator/area/%s/join', $areaID), $addresses, $opts));
        $ret  = new OperatorAreaJoinResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorSerfMembersResponse
     */
    public function AreaMembers(string $areaID, ?QueryOptions $opts = null): OperatorSerfMembersResponse
    {
        $resp = $this->_requireOK($this->_doGet(\sprintf('v1/operator/area/%s/members', $areaID), $opts));
        $ret  = new OperatorSerfMembersResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAutopilotConfigurationResponse
     */
    public function AutopilotGetConfiguration(?QueryOptions $opts = null): OperatorAutopilotConfigurationResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/autopilot/configuration', $opts));
        $ret  = new OperatorAutopilotConfigurationResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $conf
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function AutopilotSetConfiguration(AutopilotConfiguration $conf, ?WriteOptions $opts = null): ?Error
    {
        return $this->_requireOK($this->_doPut('v1/operator/autopilot/configuration', $conf, $opts))->Err;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $conf
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedBoolResponse
     */
    public function AutopilotCASConfiguration(
        AutopilotConfiguration $conf,
        ?WriteOptions $opts = null
    ): ValuedBoolResponse {
        $resp = $this->_requireOK($this->_doPut('v1/operator/autopilot/configuration', $conf, $opts));
        $ret  = new ValuedBoolResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorServerHealthsResponse
     */
    public function AutopilotServerHealth(?QueryOptions $opts = null): OperatorServerHealthsResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/autopilot/health', $opts));
        $ret  = new OperatorServerHealthsResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAutopilotStateResponse
     */
    public function AutopilotState(?QueryOptions $opts = null): OperatorAutopilotStateResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/autopilot/state', $opts));
        $ret  = new OperatorAutopilotStateResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorRaftConfigurationResponse
     */
    public function RaftGetConfiguration(?QueryOptions $opts = null): OperatorRaftConfigurationResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/operator/raft/configuration', $opts));
        $ret  = new OperatorRaftConfigurationResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $address
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function RaftRemovePeerByAddress(string $address, ?WriteOptions $opts = null): ?Error
    {
        $r = $this->_newDeleteRequest('v1/operator/raft/peer', $opts);
        $r->applyOptions($opts);
        $r->params->set('address', (string)$address);
        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\RequestResponse $resp
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
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
