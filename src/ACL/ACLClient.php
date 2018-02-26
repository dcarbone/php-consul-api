<?php namespace DCarbone\PHPConsulAPI\ACL;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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
class ACLClient extends AbstractClient {
    /**
     * @return array(
     * @type string ACL ID
     * @type \DCarbone\PHPConsulAPI\WriteMeta|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function bootstrap(): array {
        $r = new Request('PUT', 'v1/acl/bootstrap', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return ['', null, $err];
        }

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return ['', null, $err];
        }

        $wm = $this->buildWriteMeta($duration);

        return [$data, $wm, null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLEntry $acl
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type string ACL ID
     * @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function create(ACLEntry $acl, WriteOptions $options = null): array {
        $r = new Request('PUT', 'v1/acl/create', $this->config, $acl);
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

        return [$data, $wm, null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLEntry $acl
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function update(ACLEntry $acl, WriteOptions $options = null): array {
        $r = new Request('PUT', 'v1/acl/update', $this->config, $acl);
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
     * @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function destroy(string $id, WriteOptions $options = null): array {
        $r = new Request('PUT', sprintf('v1/acl/destroy/%s', $id), $this->config);
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
     * @type string cloned ACL id
     * @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function clone(string $id, WriteOptions $options = null): array {
        $r = new Request('PUT', sprintf('v1/acl/clone/%s', $id), $this->config);
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

        return [$data, $wm, null];
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\ACL\ACLEntry[]|null list of ACL entries corresponding to that ID, or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function info(string $id, QueryOptions $options = null): array {
        $r = new Request('GET', sprintf('v1/acl/info/%s', $id), $this->config);
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

        $entries = [];
        foreach ($data as $entry) {
            $entries[] = new ACLEntry($entry);
        }

        return [$entries, $qm, null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\ACL\ACLEntry[] acl entries or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function list(QueryOptions $options = null): array {
        $r = new Request('GET', 'v1/acl/list', $this->config);
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

        $entries = [];
        foreach ($data as $entry) {
            $entries[] = new ACLEntry($entry);
        }

        return [$entries, $qm, null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\ACL\ACLReplicationStatus current replication status
     * @type \DCarbone\PHPConsulAPI\QueryMeta
     * @type \DCarbone\PHPConsulAPI\Error
     * )
     */
    public function replication(QueryOptions $options = null): array {
        $r = new Request('GET', '/v1/acl/replication', $this->config);
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

        return [new ACLReplicationStatus($data), $qm, null];
    }
}