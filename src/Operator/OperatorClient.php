<?php namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
class OperatorClient extends AbstractClient {
    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Operator\RaftConfiguration|null Current Raft Configuration or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error error, if any
     * )
     */
    public function raftGetConfiguration(QueryOptions $options = null): array {
        $r = new Request('GET', 'v1/operator/raft/configuration', $this->config);
        $r->setQueryOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $qm, $err];
        }

        return [new RaftConfiguration($data), $qm, null];
    }

    /**
     * @param string $address
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return \DCarbone\PHPConsulAPI\Error|null error, if any
     */
    public function raftRemovePeerByAddress(string $address, WriteOptions $options = null) {
        $r = new Request('DELETE', 'v1/operator/raft/peer', $this->config);
        $r->setWriteOptions($options);
        $r->Params->set('address', (string)$address);

        list($_, $_, $err) = $this->requireOK($this->doRequest($r));

        return $err;
    }
}