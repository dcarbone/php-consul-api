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

use DCarbone\Go\HTTP;
use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
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
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    public function AreaCreate(Area $area, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        $r = new Request('POST', 'v1/operator/area', $this->config, $area);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        return new ValuedWriteStringResponse($data['ID'] ?? '', $this->_buildWriteMeta($duration), null);
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\Operator\Area $area
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    public function AreaUpdate(string $areaID, Area $area, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        $r = new Request('PUT', 'v1/operator/area/' . $areaID, $this->config, $area);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        return new ValuedWriteStringResponse($data['ID'] ?? '', $this->_buildWriteMeta($duration), null);
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAreasResponse
     */
    public function AreaGet(string $areaID, ?QueryOptions $opts = null): OperatorAreasResponse
    {
        $r = new Request('GET', \sprintf('v1/operator/area/%s', \urlencode($areaID)), $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new OperatorAreasResponse(null, null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());
        return new OperatorAreasResponse($data, $this->_buildQueryMeta($duration, $response, $r->getUri()), $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAreasResponse
     */
    public function AreaList(?QueryOptions $opts = null): OperatorAreasResponse
    {
        $r = new Request('GET', 'v1/operator/area', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new OperatorAreasResponse(null, null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());
        return new OperatorAreasResponse($data, $this->_buildQueryMeta($duration, $response, $r->getUri()), $err);
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function AreaDelete(string $areaID, ?WriteOptions $opts = null): WriteResponse
    {
        $r = new Request('DELETE', 'v1/operator/area/' . $areaID, $this->config, null);
        $r->applyOptions($opts);

        [$duration, $_, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new WriteResponse(null, $err);
        }

        return new WriteResponse($this->_buildWriteMeta($duration), null);
    }

    /**
     * @param string $areaID
     * @param array $addresses
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAreaJoinResponse
     */
    public function AreaJoin(string $areaID, array $addresses, ?WriteOptions $opts = null): OperatorAreaJoinResponse
    {
        $r = new Request('PUT', 'v1/operator/area/' . $areaID . '/join', $this->config, $addresses);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new OperatorAreaJoinResponse(null, null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());
        if (null !== $err) {
            return new OperatorAreaJoinResponse(null, null, $err);
        }

        return new OperatorAreaJoinResponse($data, $this->_buildWriteMeta($duration), null);
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorSerfMembersResponse
     */
    public function AreaMembers(string $areaID, ?QueryOptions $opts = null): OperatorSerfMembersResponse
    {
        $r = new Request('GET', 'v1/operator/area/' . $areaID . '/members', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new OperatorSerfMembersResponse(null, null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());
        if (null !== $err) {
            return new OperatorSerfMembersResponse(null, null, $err);
        }

        return new OperatorSerfMembersResponse($data, $this->_buildQueryMeta($duration, $response, $r->getUri()), null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAutopilotConfigurationResponse
     */
    public function AutopilotGetConfiguration(?QueryOptions $opts = null): OperatorAutopilotConfigurationResponse
    {
        $r = new Request('GET', 'v1/operator/autopilot/configuration', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new OperatorAutopilotConfigurationResponse(null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());
        if (null !== $err) {
            return new OperatorAutopilotConfigurationResponse(null, $err);
        }

        return new OperatorAutopilotConfigurationResponse($data, null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $conf
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function AutopilotSetConfiguration(AutopilotConfiguration $conf, ?WriteOptions $opts = null): ?Error
    {
        $r = new Request('PUT', 'v1/operator/autopilot/configuration', $this->config, $conf);
        $r->applyOptions($opts);

        return $this->_requireOK($this->_do($r))->Err;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $conf
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedBoolResponse
     */
    public function AutopilotCASConfiguration(
        AutopilotConfiguration $conf,
        ?WriteOptions $opts = null
    ): ValuedBoolResponse {
        $r = new Request('PUT', 'v1/operator/autopilot/configuration', $this->config, $conf);
        $r->applyOptions($opts);
        $r->params->set('cas', (string) ($conf->ModifyIndex));

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new ValuedBoolResponse(false, $err);
        }

        return new ValuedBoolResponse(false !== \strpos($response->getBody()->getContents(), 'true'), null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorServerHealthsResponse
     */
    public function AutopilotServerHealth(?QueryOptions $opts = null): OperatorServerHealthsResponse
    {
        $r = new Request('GET', 'v1/operator/autopilot/health', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new OperatorServerHealthsResponse(null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());
        if (null !== $err) {
            return new OperatorServerHealthsResponse(null, $err);
        }

        return new OperatorServerHealthsResponse($data, null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorAutopilotStateResponse
     */
    public function AutopilotState(?QueryOptions $opts = null): OperatorAutopilotStateResponse
    {
        $r = new Request(HTTP\MethodGet, 'v1/operator/autopilot/state', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new OperatorAutopilotStateResponse(null, null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());
        if (null !== $err) {
            return new OperatorAutopilotStateResponse(null, null, $err);
        }

        return new OperatorAutopilotStateResponse(
            $data,
            $this->_buildQueryMeta($duration, $response, $r->getUri()),
            null
        );
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorRaftConfigurationResponse
     */
    public function RaftGetConfiguration(?QueryOptions $opts = null): OperatorRaftConfigurationResponse
    {
        $r = new Request('GET', 'v1/operator/raft/configuration', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new OperatorRaftConfigurationResponse(null, null, $err);
        }

        [$data, $err] = $this->_decodeBody($response->getBody());

        if (null !== $err) {
            return new OperatorRaftConfigurationResponse(null, null, $err);
        }

        $qm = $this->_buildQueryMeta($duration, $response, $r->getUri());

        return new OperatorRaftConfigurationResponse($data, $qm, null);
    }

    /**
     * @param string $address
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function RaftRemovePeerByAddress(string $address, ?WriteOptions $opts = null): ?Error
    {
        $r = new Request('DELETE', 'v1/operator/raft/peer', $this->config, null);
        $r->applyOptions($opts);
        $r->params->set('address', (string) $address);

        return $this->_requireOK($this->_do($r))->Err;
    }
}
