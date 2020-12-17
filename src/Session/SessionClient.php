<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Session;

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
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class SessionClient
 * @package DCarbone\PHPConsulAPI\Session
 */
class SessionClient extends AbstractClient
{
    /**
     * @param \DCarbone\PHPConsulAPI\Session\SessionEntry|null $sessionEntry
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function CreateNoChecks(?SessionEntry $sessionEntry = null, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        if (null === $sessionEntry) {
            $body = new SessionEntry();
        } else {
            $body = clone $sessionEntry;
        }

        $body->Checks = [];
        $body->NodeChecks = [];
        $body->ServiceChecks = [];

        return $this->_create('v1/session/create', $body, $opts);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Session\SessionEntry|null $sessionEntry
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Create(?SessionEntry $sessionEntry = null, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_create('v1/session/create', $sessionEntry, $opts);
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Destroy(string $id, ?WriteOptions $opts = null): WriteResponse
    {
        $r = new Request(HTTP\MethodPut, sprintf('v1/session/destroy/%s', $id), $this->config, null);
        $r->applyOptions($opts);

        [$duration, $_, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new WriteResponse(null, $err);
        }

        return new WriteResponse($this->buildWriteMeta($duration), null);
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntriesWriteResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Renew(string $id, ?WriteOptions $opts = null): SessionEntriesWriteResponse
    {
        $r = new Request(HTTP\MethodPut, sprintf('v1/session/renew/%s', $id), $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list ($duration, $response, $err) = $this->_do($r);
        if (null !== $err) {
            return new SessionEntriesWriteResponse(null, null, $err);
        }

        $wm = $this->buildWriteMeta($duration);

        $code = $response->getStatusCode();

        if (404 === $code) {
            return new SessionEntriesWriteResponse(null, $wm, null);
        }

        if (200 !== $code) {
            return new SessionEntriesWriteResponse(null,
                $wm,
                new Error(sprintf(
                    '%s::renew - Unexpected response code %d.  Reason: %s',
                    get_class($this),
                    $code,
                    $response->getReasonPhrase()
                )));
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        return new SessionEntriesWriteResponse($data, $wm, $err);
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntriesQueryResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Info(string $id, ?QueryOptions $opts = null): SessionEntriesQueryResponse
    {
        return $this->_get(sprintf('v1/session/info/%s', $id), $opts);
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntriesQueryResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Node(string $node, ?QueryOptions $opts = null): SessionEntriesQueryResponse
    {
        return $this->_get(sprintf('v1/session/node/%s', $node), $opts);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntriesQueryResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function List(?QueryOptions $opts = null): SessionEntriesQueryResponse
    {
        return $this->_get('v1/session/list', $opts);
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntriesQueryResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _get(string $path, ?QueryOptions $opts): SessionEntriesQueryResponse
    {
        $r = new Request(HTTP\MethodGet, $path, $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new SessionEntriesQueryResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());
        return new SessionEntriesQueryResponse($data, $qm, $err);
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\Session\SessionEntry $entry
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _create(string $path, SessionEntry $entry, ?WriteOptions $opts): ValuedWriteStringResponse
    {
        $r = new Request(HTTP\MethodPut, $path, $this->config, $entry->_toAPIPayload());
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        $wm = $this->buildWriteMeta($duration);

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new ValuedWriteStringResponse('', $wm, $err);
        }

        return new ValuedWriteStringResponse($data['ID'] ?? '', $wm, null);
    }
}