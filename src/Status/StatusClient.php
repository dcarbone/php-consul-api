<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Status;

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
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\ValuedStringResponse;
use DCarbone\PHPConsulAPI\ValuedStringsResponse;

/**
 * Class StatusClient
 */
class StatusClient extends AbstractClient
{
    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedStringResponse
     */
    public function LeaderWithQueryOptions(?QueryOptions $opts): ValuedStringResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/status/leader', $opts));
        $ret  = new ValuedStringResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedStringResponse
     */
    public function Leader(): ValuedStringResponse
    {
        return $this->LeaderWithQueryOptions(null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedStringsResponse
     */
    public function PeersWithQueryOptions(?QueryOptions $opts): ValuedStringsResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/status/peers', $opts));
        $ret  = new ValuedStringsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedStringsResponse
     */
    public function Peers(): ValuedStringsResponse
    {
        return $this->PeersWithQueryOptions(null);
    }
}
