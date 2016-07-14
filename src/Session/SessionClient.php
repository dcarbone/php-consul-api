<?php namespace DCarbone\PHPConsulAPI\Session;

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

use DCarbone\PHPConsulAPI\AbstractApiClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\HttpRequest;
use DCarbone\PHPConsulAPI\Hydrator;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class SessionClient
 * @package DCarbone\PHPConsulAPI\Session
 */
class SessionClient extends AbstractApiClient
{
    const SessionBehaviorRelease = 'release';
    const SessionBehaviorDelete = 'delete';

    /**
     * @param SessionEntry|null $sessionEntry
     * @param WriteOptions|null $writeOptions
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
            $sessionEntry->Checks = array();

        $r = new HttpRequest('put', 'v1/session/create', $this->_Config, $sessionEntry);
        $r->setWriteOptions($writeOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        if (null !== $err)
            return ['', $wm, $err];

        list($data, $err) = $this->decodeBody($response);
        if (null !== $err)
            return ['', $wm, $err];

        return [$data['ID'], $wm, null];
    }

    /**
     * @param SessionEntry|null $sessionEntry
     * @param WriteOptions|null $writeOptions
     * @return array(
     *  @type string
     *  @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function create(SessionEntry $sessionEntry = null, WriteOptions $writeOptions = null)
    {
        $r = new HttpRequest('put', 'v1/session/create', $this->_Config, $sessionEntry);
        $r->setWriteOptions($writeOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        if (null !== $err)
            return ['', $wm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return ['', $wm, $err];

        return [$data['ID'], $wm, null];
    }

    /**
     * @param string $id
     * @param WriteOptions|null $writeOptions
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

        $r = new HttpRequest('put', sprintf('v1/session/destroy/%s', rawurlencode($id)), $this->_Config);
        $r->setWriteOptions($writeOptions);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        return [$wm, $err];
    }

    /**
     * @param string $id
     * @param WriteOptions|null $writeOptions
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

        $r = new HttpRequest('put', sprintf('v1/session/renew/%s', rawurlencode($id)), $this->_Config);
        $r->setWriteOptions($writeOptions);

        /** @var \Dcarbone\PHPConsulAPI\HttpResponse $response */
        list ($duration, $response, $err) = $this->doRequest($r);
        $wm = $this->buildWriteMeta($duration);

        if (null !== $err)
            return [null, $wm, $err];

        if (404 === $response->httpCode)
            return [null, $wm, null];

        if (200 !== $response->httpCode)
        {
            return [null, $wm, new Error(sprintf(
                '%s::renew - Unexpected response code %d',
                get_class($this),
                $response->httpCode
            ))];
        }

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $wm, $err];

        return [$this->_hydrateEntries($data), $wm, null];
    }

    /**
     * @param string $id
     * @param QueryOptions|null $queryOptions
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

        $r = new HttpRequest('get', sprintf('v1/session/info/%s', rawurlencode($id)), $this->_Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        if (!is_array($data) || 0 === count($data))
            return [null, $qm, null];

        return [$this->_hydrateEntries($data), $qm, null];
    }

    /**
     * @param string $node
     * @param QueryOptions|null $queryOptions
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

        $r = new HttpRequest('get', sprintf('v1/session/node/%s', rawurlencode($node)), $this->_Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

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
        $r = new HttpRequest('get', 'v1/sesion/list', $this->_Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

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
        $entries = array();
        foreach($data as $entry)
        {
            $entries[] = Hydrator::SessionEntry($entry);
        }

        return $entries;
    }
}