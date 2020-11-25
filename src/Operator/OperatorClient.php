<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class OperatorClient
 * @package DCarbone\PHPConsulAPI\Operator
 */
class OperatorClient extends AbstractClient
{
    /**
     * @param \DCarbone\PHPConsulAPI\Operator\Area $area
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return array(
     * @type string
     * @type \DCarbone\PHPConsulAPI\WriteMeta|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AreaCreate(Area $area, WriteOptions $opts = null): array
    {
        $r = new Request('POST', 'v1/operator/area', $this->config, $area);
        $r->setWriteOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return ['', null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return ['', null, $err];
        }

        return [$data['ID'] ?? '', $this->buildWriteMeta($duration), null];
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\Operator\Area $area
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return array(
     * @type string
     * @type \DCarbone\PHPConsulAPI\WriteMeta|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AreaUpdate(string $areaID, Area $area, WriteOptions $opts = null): array
    {
        $r = new Request('PUT', 'v1/operator/area/' . $areaID, $this->config, $area);
        $r->setWriteOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return ['', null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return ['', null, $err];
        }

        return [$data['ID'] ?? '', $this->buildWriteMeta($duration), null];
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Operator\Area[]|null
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AreaGet(string $areaID, QueryOptions $opts = null): array
    {
        $r = new Request('GET', 'v1/operator/area/' . $areaID, $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        $resp = [];
        foreach ($data as $datum) {
            $resp = new Area($datum);
        }

        return [$resp, $this->buildQueryMeta($duration, $response, $r->getUri()), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Operator\Area[]|null
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AreaList(QueryOptions $opts = null): array
    {
        $r = new Request('GET', 'v1/operator/area', $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        $resp = [];
        foreach ($data as $datum) {
            $resp = new Area($datum);
        }

        return [$resp, $this->buildQueryMeta($duration, $response, $r->getUri()), null];
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AreaDelete(string $areaID, WriteOptions $opts = null): array
    {
        $r = new Request('DELETE', 'v1/operator/area/' . $areaID, $this->config);
        $r->setWriteOptions($opts);

        [$duration, $_, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @param string $areaID
     * @param array $addresses
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Operator\AreaJoinResponse[]|null
     * @type \DCarbone\PHPConsulAPI\WriteMeta|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AreaJoin(string $areaID, array $addresses, WriteOptions $opts = null): array
    {
        $r = new Request('PUT', 'v1/operator/area/' . $areaID . '/join', $this->config, $addresses);
        $r->setWriteOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        $resp = [];
        foreach ($data as $datum) {
            $resp[] = new AreaJoinResponse($datum);
        }

        return [$resp, $this->buildWriteMeta($duration), null];
    }

    /**
     * @param string $areaID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Operator\SerfMember[]|null
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AreaMembers(string $areaID, QueryOptions $opts = null): array
    {
        $r = new Request('GET', 'v1/operator/area/' . $areaID . '/members', $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        $resp = [];
        foreach ($data as $datum) {
            $resp[] = new SerfMember($datum);
        }

        return [$resp, $this->buildQueryMeta($duration, $response, $r->getUri()), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AutopilotGetConfiguration(QueryOptions $opts = null): array
    {
        $r = new Request('GET', 'v1/operator/autopilot/configuration', $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, $err];
        }

        return [new AutopilotConfiguration($data), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $conf
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function AutopilotSetConfiguration(AutopilotConfiguration $conf, WriteOptions $opts = null)
    {
        $r = new Request('PUT', 'v1/operator/autopilot/configuration', $this->config, $conf);
        $r->setWriteOptions($opts);

        [$_, $_, $err] = $this->requireOK($this->doRequest($r));
        return $err;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $conf
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return array(
     * @type bool
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AutopilotCASConfiguration(AutopilotConfiguration $conf, WriteOptions $opts = null): array
    {
        $r = new Request('PUT', 'v1/operator/autopilot/configuration', $this->config, $conf);
        $r->setWriteOptions($opts);
        $r->params->set('cas', strval($conf->ModifyIndex));

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [false, $err];
        }

        return [false !== strpos($response->getBody()->getContents(), 'true'), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Operator\ServerHealth[]|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function AutopilotServerHealth(QueryOptions $opts = null): array
    {
        $r = new Request('GET', 'v1/operator/autopilot/health', $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, $err];
        }

        return [new OperatorHealthReply($data), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Operator\RaftConfiguration|null Current Raft Configuration or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error error, if any
     * )
     */
    public function RaftGetConfiguration(QueryOptions $opts = null): array
    {
        $r = new Request('GET', 'v1/operator/raft/configuration', $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        return [new RaftConfiguration($data), $qm, null];
    }

    /**
     * @param string $address
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Error|null error, if any
     */
    public function RaftRemovePeerByAddress(string $address, WriteOptions $opts = null)
    {
        $r = new Request('DELETE', 'v1/operator/raft/peer', $this->config);
        $r->setWriteOptions($opts);
        $r->params->set('address', (string)$address);

        [$_, $_, $err] = $this->requireOK($this->doRequest($r));

        return $err;
    }
}