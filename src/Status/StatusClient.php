<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Status;

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
     * @return \DCarbone\PHPConsulAPI\ValuedStringResponse
     */
    public function LeaderWithQueryOptions(?QueryOptions $opts): ValuedStringResponse
    {
        $r = new Request('GET', 'v1/status/leader', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));

        if (null !== $err) {
            return new ValuedStringResponse('', $err);
        }

        $d = $this->decodeBody($response->getBody());
        return new ValuedStringResponse($d->Decoded, $d->Err);
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
     * @return \DCarbone\PHPConsulAPI\ValuedStringsResponse
     */
    public function PeersWithQueryOptions(?QueryOptions $opts): ValuedStringsResponse
    {
        $r = new Request('GET', 'v1/status/peers', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->_requireOK($this->_do($r));

        if (null !== $err) {
            return new ValuedStringsResponse(null, $err);
        }
        $d = $this->decodeBody($response->getBody());
        return new ValuedStringsResponse($d->Decoded, $d->Err);
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
