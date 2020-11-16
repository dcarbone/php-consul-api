<?php namespace DCarbone\PHPConsulAPI\Status;

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
use DCarbone\PHPConsulAPI\Request;

/**
 * Class StatusClient
 * @package DCarbone\PHPConsulAPI\Status
 */
class StatusClient extends AbstractClient
{
    /**
     * @return array(
     * @type string
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Leader(): array
    {
        $r = new Request('GET', 'v1/status/leader', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err) {
            return ['', $err];
        }

        return $this->decodeBody($response->getBody());
    }

    /**
     * @return array|null
     */
    public function Peers()
    {
        $r = new Request('GET', 'v1/status/peers', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err) {
            return [null, $err];
        }

        return $this->decodeBody($response->getBody());
    }
}