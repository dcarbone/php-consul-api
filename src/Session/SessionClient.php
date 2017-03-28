<?php namespace DCarbone\PHPConsulAPI\Session;

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
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class SessionClient
 * @package DCarbone\PHPConsulAPI\Session
 */
class SessionClient extends AbstractClient
{
    const SessionBehaviorRelease = 'release';
    const SessionBehaviorDelete = 'delete';

    /**
     * @param SessionEntry|null $sessionEntry
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $writeOptions
     * @return array(
     *  @type string
     *  @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function createNoChecks(SessionEntry $sessionEntry = null, WriteOptions $writeOptions = null)
    {
        if (null === $sessionEntry)
            $sessionEntry = new SessionEntry;
        else
            $sessionEntry->Checks = [];

        $r = new Request('put', 'v1/session/create', $this->c, $sessionEntry);
        $r->setWriteOptions($writeOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err)
            return ['', null, $err];

        $wm = $this->buildWriteMeta($duration);

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err)
            return ['', $wm, $err];

        return [$data['ID'], $wm, null];
    }

    /**
     * @param SessionEntry|null $sessionEntry
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $writeOptions
     * @return array(
     *  @type string
     *  @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function create(SessionEntry $sessionEntry = null, WriteOptions $writeOptions = null)
    {
        $r = new Request('put', 'v1/session/create', $this->c, $sessionEntry);
        $r->setWriteOptions($writeOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err)
            return ['', null, $err];

        $wm = $this->buildWriteMeta($duration);

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err)
            return ['', $wm, $err];

        return [$data['ID'], $wm, null];
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $writeOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\WriteMeta|null write metadata or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function destroy($id, WriteOptions $writeOptions = null)
    {
        if (!is_string($id))
        {
            return [null, new Error(sprintf(
                '%s::destroy - "$id" must be string, %s seen.',
                get_class($this),
                gettype($id)
            ))];
        }

        $r = new Request('put', sprintf('v1/session/destroy/%s', $id), $this->c);
        $r->setWriteOptions($writeOptions);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err)
            return [null, $err];

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $writeOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\Session\SessionEntry[]|null list of session entries or null on error
     *  @type \DCarbone\PHPConsulAPI\WriteMeta|null write metadata or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function renew($id, WriteOptions $writeOptions = null)
    {
        if (!is_string($id))
        {
            return [null, null, new Error(sprintf(
                '%s::renew - "$id" must be string, %s seen.',
                get_class($this),
                gettype($id)
            ))];
        }

        $r = new Request('put', sprintf('v1/session/renew/%s', $id), $this->c);
        $r->setWriteOptions($writeOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list ($duration, $response, $err) = $this->doRequest($r);
        if (null !== $err)
            return [null, null, $err];

        $wm = $this->buildWriteMeta($duration);

        $code = $response->getStatusCode();

        if (404 === $code)
            return [null, $wm, null];

        if (200 !== $code)
        {
            return [null, $wm, new Error(sprintf(
                '%s::renew - Unexpected response code %d.  Reason: %s',
                get_class($this),
                $code,
                $response->getReasonPhrase()
            ))];
        }

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err)
            return [null, $wm, $err];

        return [$this->_hydrateEntries($data), $wm, null];
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $queryOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\Session\SessionEntry[]|null list of session entries or null on error / empty response
     *  @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function info($id, QueryOptions $queryOptions = null)
    {
        if (!is_string($id))
        {
            return [null, null, new Error(sprintf(
                '%s::info - "$id" must be string, %s seen.',
                get_class($this),
                gettype($id)
            ))];
        }

        $r = new Request('get', sprintf('v1/session/info/%s', $id), $this->c);
        $r->setQueryOptions($queryOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err)
            return [null, null, $err];

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err)
            return [null, $qm, $err];

        if (!is_array($data) || 0 === count($data))
            return [null, $qm, null];

        return [$this->_hydrateEntries($data), $qm, null];
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $queryOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\Session\SessionEntry[]|null list of session entries or null on error
     *  @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function node($node, QueryOptions $queryOptions = null)
    {
        if (!is_string($node))
        {
            return [null, null, new Error(sprintf(
                '%s::node - "$node" must be string, %s seen.',
                get_class($this),
                gettype($node)
            ))];
        }

        $r = new Request('get', sprintf('v1/session/node/%s', $node), $this->c);
        $r->setQueryOptions($queryOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err)
            return [null, null, $err];

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err)
            return [null, $qm, $err];

        return [$this->_hydrateEntries($data), $qm, null];
    }

    /**
     * @param QueryOptions|null $queryOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\Session\SessionEntry[]|null list of session entries or null on error
     *  @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function listSessions(QueryOptions $queryOptions = null)
    {
        $r = new Request('get', 'v1/session/list', $this->c);
        $r->setQueryOptions($queryOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err)
            return [null, null, $err];

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err)
            return [null, $qm, $err];

        return [$this->_hydrateEntries($data), $qm, null];
    }

    /**
     * @param array $data
     * @return array
     */
    private function _hydrateEntries(array $data)
    {
        $entries = [];
        foreach($data as $entry)
        {
            $entries[] = new SessionEntry($entry);
        }

        return $entries;
    }
}