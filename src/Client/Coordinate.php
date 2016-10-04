<?php namespace DCarbone\PHPConsulAPI\Client;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Http\Request;
use DCarbone\PHPConsulAPI\Hydration\Hydrator;
use DCarbone\PHPConsulAPI\Model\QueryOptions;

/**
 * Class Coordinate
 * @package DCarbone\PHPConsulAPI\Client
 */
class Coordinate extends AbstractClient
{
    /**
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\Model\CoordinateDatacenterMap[]|null datacenter map or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if an
     * )
     */
    public function datacenters()
    {
        $r = new Request('get', 'v1/coordinate/datacenters', $this->Config);

        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err)
            return [null, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $err];

        $datacenters = array();
        foreach($data as $v)
        {
            $datacenters[] = Hydrator::CoordinateDatacenterMap($v);
        }

        return [$data, null];
    }

    /**
     * @param QueryOptions|null $queryOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\Model\CoordinateEntry[]|null coordinate list or null on error
     *  @type \DCarbone\PHPConsulAPI\Model\QueryMeta query metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function nodes(QueryOptions $queryOptions = null)
    {
        $r = new Request('get', 'v1/coordinate/nodes', $this->Config);
        $r->setQueryOptions($queryOptions);

        list ($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);
        if (null !== $err)
            return [null, $qm, $err];

        $coordinates = array();
        foreach($data as $coord)
        {
            $coordinates[] = Hydrator::CoordinateEntry($coord);
        }

        return [$coordinates, $qm, null];
    }
}