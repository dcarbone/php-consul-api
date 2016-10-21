<?php namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class ACLClient
 * @package DCarbone\PHPConsulAPI\ACL
 */
class ACLClient extends AbstractClient
{
    /**
     * @param ACLEntry $acl
     * @param WriteOptions|null $writeOptions
     * @return array(
     * @type string ACL ID
     * @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function create(ACLEntry $acl, WriteOptions $writeOptions = null)
    {
        $r = new Request('put', 'v1/acl/create', $this->c, $acl);
        $r->setWriteOptions($writeOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        if (null !== $err)
            return [null, $wm, $err];

        list($data, $err) = $this->decodeBody($response->getBody());

        return [$data, $wm, $err];
    }

    /**
     * @param ACLEntry $acl
     * @param WriteOptions|null $writeOptions
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function update(ACLEntry $acl, WriteOptions $writeOptions = null)
    {
        $r = new Request('PUT', 'v1/acl/update', $this->c, $acl);
        $r->setWriteOptions($writeOptions);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));

        return [$this->buildWriteMeta($duration), $err];
    }

    /**
     * @param string $id
     * @param WriteOptions|null $writeOptions
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function destroy($id, WriteOptions $writeOptions = null)
    {
        $r = new Request('PUT', sprintf('v1/acl/destroy/%s', $id), $this->c);
        $r->setWriteOptions($writeOptions);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));

        return [$this->buildWriteMeta($duration), $err];
    }

    /**
     * @param string $id
     * @param WriteOptions|null $writeOptions
     * @return array(
     * @type string cloned ACL id
     * @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function cloneACL($id, WriteOptions $writeOptions = null)
    {
        $r = new Request('PUT', sprintf('v1/acl/clone/%s', $id), $this->c);
        $r->setWriteOptions($writeOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        if (null !== $err)
            return [null, $wm, $err];

        list($data, $err) = $this->decodeBody($response->getBody());

        return [$data, $wm, $err];
    }

    /**
     * @param string $id
     * @param QueryOptions|null $queryOptions
     * @return array(
     * @type \DCarbone\PHPConsulAPI\ACL\ACLEntry[]|null list of ACL entries corresponding to that ID, or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function info($id, QueryOptions $queryOptions = null)
    {
        $r = new Request('GET', sprintf('v1/acl/info/%s', $id), $this->c);
        $r->setQueryOptions($queryOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());
        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err)
            return [null, $qm, $err];

        $entries = [];
        foreach($data as $entry)
        {
            $entries[] = new ACLEntry($entry);
        }

        return [$entries, $qm, null];
    }

    /**
     * @param QueryOptions|null $queryOptions
     * @return array(
     * @type \DCarbone\PHPConsulAPI\ACL\ACLEntry[] acl entries or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function listACLs(QueryOptions $queryOptions = null)
    {
        $r = new Request('GET', 'v1/acl/list', $this->c);
        $r->setQueryOptions($queryOptions);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err)
            return [null, $qm, $err];

        $entries = [];
        foreach($data as $entry)
        {
            $entries[] = new ACLEntry($entry);
        }

        return [$entries, $qm, null];
    }
}