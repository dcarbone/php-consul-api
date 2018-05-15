<?php namespace DCarbone\PHPConsulAPI\Coordinate;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class CoordinateClient
 * @package DCarbone\PHPConsulAPI\Coordinate
 */
class CoordinateClient extends AbstractClient {
    /**
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Coordinate\CoordinateDatacenterMap[]|null datacenter map or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if an
     * )
     */
    public function Datacenters(): array {
        $r = new Request('GET', 'v1/coordinate/datacenters', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err) {
            return [null, $err];
        }

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $err];
        }

        $datacenters = [];
        foreach ($data as $v) {
            $datacenters[] = new CoordinateDatacenterMap($v);
        }

        return [$data, null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry[]|null coordinate list or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Nodes(QueryOptions $options = null): array {
        $r = new Request('GET', 'v1/coordinate/nodes', $this->config);
        $r->setQueryOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list ($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, $qm, $err];
        }

        $coordinates = [];
        foreach ($data as $coord) {
            $coordinates[] = new CoordinateEntry($coord);
        }

        return [$coordinates, $qm, null];
    }
}