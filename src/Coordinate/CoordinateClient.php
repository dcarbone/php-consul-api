<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Coordinate;

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
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class CoordinateClient
 */
class CoordinateClient extends AbstractClient
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Coordinate\CoordinateDatacentersResponse
     */
    public function Datacenters(): CoordinateDatacentersResponse
    {
        $resp = $this->_doGet('v1/coordinate/datacenters', null);
        $ret  = new CoordinateDatacentersResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Coordinate\CoordinateEntriesResponse
     */
    public function Nodes(?QueryOptions $opts = null): CoordinateEntriesResponse
    {
        $resp = $this->_doGet('v1/coordinate/nodes', $opts);
        $ret  = new CoordinateEntriesResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry $coordinateEntry
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Update(CoordinateEntry $coordinateEntry, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut('v1/coordinate/update', $coordinateEntry, $opts);
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Coordinate\CoordinateEntriesResponse
     */
    public function Node(string $node, ?QueryOptions $opts = null): CoordinateEntriesResponse
    {
        $resp = $this->_doGet(\sprintf('v1/coordinate/node/%s', $node), $opts);
        $ret  = new CoordinateEntriesResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }
}
