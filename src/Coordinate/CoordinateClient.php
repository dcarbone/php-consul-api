<?php namespace DCarbone\PHPConsulAPI\Coordinate;

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

use DCarbone\PHPConsulAPI\AbstractConsulClient;
use DCarbone\PHPConsulAPI\Hydrator;
use DCarbone\PHPConsulAPI\Request;

/**
 * Class CoordinateClient
 * @package DCarbone\PHPConsulAPI\Coordinate
 */
class CoordinateClient extends AbstractConsulClient
{
    /**
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\Coordinate\CoordinateDatacenterMap[]|null datacenter map or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if an
     * )
     */
    public function datacenters()
    {
        $r = new Request('get', 'v1/coordinate/datacenters', $this->_Config);

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
}