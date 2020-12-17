<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Coordinate;

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

use DCarbone\Go\HTTP;
use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class CoordinateClient
 * @package DCarbone\PHPConsulAPI\Coordinate
 */
class CoordinateClient extends AbstractClient
{
    /**
     * @return \DCarbone\PHPConsulAPI\Coordinate\CoordinateDatacentersResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Datacenters(): CoordinateDatacentersResponse
    {
        $r = new Request('GET', 'v1/coordinate/datacenters', $this->config, null);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));

        if (null !== $err) {
            return new CoordinateDatacentersResponse(null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        return new CoordinateDatacentersResponse($data, $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Coordinate\CoordinateEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Nodes(?QueryOptions $opts = null): CoordinateEntriesResponse
    {
        $r = new Request('GET', 'v1/coordinate/nodes', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new CoordinateEntriesResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());
        return new CoordinateEntriesResponse($data, $qm, $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry $coordinateEntry
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Update(CoordinateEntry $coordinateEntry, ?WriteOptions $opts = null): WriteResponse
    {
        $r = new Request(HTTP\MethodPut, 'v1/coordinate/update', $this->config, $coordinateEntry);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $_, $err] = $this->_requireOK($this->_do($r));

        return new WriteResponse($this->buildWriteMeta($duration), $err);
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Coordinate\CoordinateEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Node(string $node, ?QueryOptions $opts = null): CoordinateEntriesResponse
    {
        $r = new Request(HTTP\MethodGet, sprintf('v1/coordinate/node/%s', $node), $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new CoordinateEntriesResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());
        return new CoordinateEntriesResponse($data, $qm, $err);
    }
}