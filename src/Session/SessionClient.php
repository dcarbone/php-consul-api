<?php namespace DCarbone\PHPConsulAPI\Session;

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
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class SessionClient
 * @package DCarbone\PHPConsulAPI\Session
 */
class SessionClient extends AbstractClient {
    public const SessionBehaviorRelease = 'release';
    public const SessionBehaviorDelete = 'DELETE';

    /**
     * @param SessionEntry|null $sessionEntry
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type string
     * @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function CreateNoChecks(SessionEntry $sessionEntry = null, WriteOptions $options = null): array {
        if (null === $sessionEntry) {
            $sessionEntry = new SessionEntry;
        } else {
            $sessionEntry->Checks = [];
        }

        $r = new Request('PUT', 'v1/session/create', $this->config, $sessionEntry);
        $r->setWriteOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return ['', null, $err];
        }

        $wm = $this->buildWriteMeta($duration);

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return ['', $wm, $err];
        }

        return [$data['ID'] ?? '', $wm, null];
    }

    /**
     * @param SessionEntry|null $sessionEntry
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type string
     * @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Create(SessionEntry $sessionEntry = null, WriteOptions $options = null): array {
        $r = new Request('PUT', 'v1/session/create', $this->config, $sessionEntry);
        $r->setWriteOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return ['', null, $err];
        }

        $wm = $this->buildWriteMeta($duration);

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return ['', $wm, $err];
        }

        return [$data['ID'] ?? '', $wm, null];
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta|null write metadata or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Destroy(string $id, WriteOptions $options = null): array {
        $r = new Request('PUT', sprintf('v1/session/destroy/%s', $id), $this->config);
        $r->setWriteOptions($options);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Session\SessionEntry[]|null list of session entries or null on error
     * @type \DCarbone\PHPConsulAPI\WriteMeta|null write metadata or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Renew(string $id, WriteOptions $options = null): array {
        $r = new Request('PUT', sprintf('v1/session/renew/%s', $id), $this->config);
        $r->setWriteOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list ($duration, $response, $err) = $this->doRequest($r);
        if (null !== $err) {
            return [null, null, $err];
        }

        $wm = $this->buildWriteMeta($duration);

        $code = $response->getStatusCode();

        if (404 === $code) {
            return [null, $wm, null];
        }

        if (200 !== $code) {
            return [null,
                $wm,
                new Error(sprintf(
                    '%s::renew - Unexpected response code %d.  Reason: %s',
                    get_class($this),
                    $code,
                    $response->getReasonPhrase()
                ))];
        }

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $wm, $err];
        }

        return [$this->hydrateEntries($data), $wm, null];
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Session\SessionEntry[]|null list of session entries or null on error / empty response
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Info(string $id, QueryOptions $options = null): array {
        $r = new Request('GET', sprintf('v1/session/info/%s', $id), $this->config);
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

        if (!is_array($data) || 0 === count($data)) {
            return [null, $qm, null];
        }

        return [$this->hydrateEntries($data), $qm, null];
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Session\SessionEntry[]|null list of session entries or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Node(string $node, QueryOptions $options = null): array {
        $r = new Request('GET', sprintf('v1/session/node/%s', $node), $this->config);
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

        return [$this->hydrateEntries($data), $qm, null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Session\SessionEntry[]|null list of session entries or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function List(QueryOptions $options = null): array {
        $r = new Request('GET', 'v1/session/list', $this->config);
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

        return [$this->hydrateEntries($data), $qm, null];
    }

    /**
     * @param array $data
     * @return array
     */
    private function hydrateEntries(array $data) {
        $entries = [];
        foreach ($data as $entry) {
            $entries[] = new SessionEntry($entry);
        }

        return $entries;
    }
}