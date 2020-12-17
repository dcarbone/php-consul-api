<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

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
use DCarbone\PHPConsulAPI\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class PreparedQueryClient
 * @package DCarbone\PHPConsulAPI\PreparedQuery
 */
class PreparedQueryClient extends AbstractClient
{
    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition $query
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Create(PreparedQueryDefinition $query, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        $r = new Request('POST', 'v1/query', $this->config, $query);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        return new ValuedWriteStringResponse((string)$response->getBody(), $this->buildWriteMeta($duration), null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition $query
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Update(PreparedQueryDefinition $query, ?WriteOptions $opts = null): WriteResponse
    {
        $r = new Request('PUT', 'v1/query', $this->config, $query);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $_, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new WriteResponse(null, $err);
        }

        return new WriteResponse($this->buildWriteMeta($duration), null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinitionsResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function List(?QueryOptions $opts = null): PreparedQueryDefinitionsResponse
    {
        $r = new Request('GET', 'v1/query', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new PreparedQueryDefinitionsResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());
        return new PreparedQueryDefinitionsResponse($data, $qm, $err);
    }

    /**
     * @param string $queryID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinitionsResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Get(string $queryID, ?QueryOptions $opts = null): PreparedQueryDefinitionsResponse
    {
        $r = new Request('GET', sprintf('v1/query/%s', $queryID), $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new PreparedQueryDefinitionsResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());
        return new PreparedQueryDefinitionsResponse($data, $qm, $err);
    }

    /**
     * @param string $queryID
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Delete(string $queryID, ?WriteOptions $opts = null): WriteResponse
    {
        $r = new Request('DELETE', sprintf('v1/query/%s', $queryID), $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new WriteResponse(null, $err);
        }

        [$_, $err] = $this->decodeBody($response->getBody());

        return new WriteResponse($this->buildWriteMeta($duration), $err);
    }

    /**
     * @param string $queryIDOrName
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryExecuteResponseResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Execute(string $queryIDOrName, ?QueryOptions $opts = null): PreparedQueryExecuteResponseResponse
    {
        $r = new Request('GET', sprintf('v1/query/%s/execute', $queryIDOrName), $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new PreparedQueryExecuteResponseResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());
        return new PreparedQueryExecuteResponseResponse($data, $qm, $err);
    }
}
