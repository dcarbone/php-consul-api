<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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
 * Class ACLClient
 * @package DCarbone\PHPConsulAPI\ACL
 */
class ACLClient extends AbstractClient
{
    /**
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Bootstrap(): ValuedWriteStringResponse
    {
        $r = new Request('PUT', 'v1/acl/bootstrap', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        $wm = $this->buildWriteMeta($duration);

        return new ValuedWriteStringResponse($data, $wm, null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLEntry $acl
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Create(ACLEntry $acl,?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        $r = new Request('PUT', 'v1/acl/create', $this->config, $acl);
        $r->setWriteOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        $wm = $this->buildWriteMeta($duration);

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        return new ValuedWriteStringResponse($data, $wm, null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLEntry $acl
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Update(ACLEntry $acl,?WriteOptions $opts = null): WriteResponse
    {
        $r = new Request('PUT', 'v1/acl/update', $this->config, $acl);
        $r->setWriteOptions($opts);

        [$duration, $_, $err] = $this->requireOK($this->do($r));

        return new WriteResponse($this->buildWriteMeta($duration), $err);
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Destroy(string $id,?WriteOptions $opts = null): WriteResponse
    {
        $r = new Request('PUT', sprintf('v1/acl/destroy/%s', $id), $this->config);
        $r->setWriteOptions($opts);

        [$duration, $_, $err] = $this->requireOK($this->do($r));

        return new WriteResponse($this->buildWriteMeta($duration), $err);
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Clone(string $id,?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        $r = new Request('PUT', sprintf('v1/acl/clone/%s', $id), $this->config);
        $r->setWriteOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new ValuedWriteStringResponse('', null, $err);
        }

        $wm = $this->buildWriteMeta($duration);

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new ValuedWriteStringResponse('', $wm, $err);
        }

        return new ValuedWriteStringResponse($data, $wm, null);
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Info(string $id,?QueryOptions $opts = null): ACLEntriesResponse
    {
        $r = new Request('GET', sprintf('v1/acl/info/%s', $id), $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new ACLEntriesResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new ACLEntriesResponse(null, $qm, $err);
        }

        return new ACLEntriesResponse($data, $qm, null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function List(?QueryOptions $opts = null): ACLEntriesResponse
    {
        $r = new Request('GET', 'v1/acl/list', $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new ACLEntriesResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new ACLEntriesResponse(null, $qm, $err);
        }

        return new ACLEntriesResponse($data, $qm, null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ACL\ACLReplicationStatusResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Replication(?QueryOptions $opts = null): ACLReplicationStatusResponse
    {
        $r = new Request('GET', '/v1/acl/replication', $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new ACLReplicationStatusResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new ACLReplicationStatusResponse(null, $qm, $err);
        }

        return new ACLReplicationStatusResponse($data, $qm, null);
    }
}