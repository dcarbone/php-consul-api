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

use DCarbone\Go\HTTP;
use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class ACLClient
 */
class ACLClient extends AbstractClient
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    public function Bootstrap(): ValuedWriteStringResponse
    {
        return $this->_doPutValuedStr('v1/acl/bootstrap', null, null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLEntry $acl
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    public function Create(ACLEntry $acl, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_doPutValuedStr('v1/acl/create', $acl, $opts);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLEntry $acl
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Update(ACLEntry $acl, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut('v1/acl/update', $acl, $opts);
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Destroy(string $id, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut(\sprintf('v1/acl/destroy/%s', $id), null, $opts);
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    public function Clone(string $id, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_doPutValuedStr(\sprintf('v1/acl/clone/%s', $id), null, $opts);
    }

    /**
     * @param string $id
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntriesResponse
     */
    public function Info(string $id, ?QueryOptions $opts = null): ACLEntriesResponse
    {
        $r = new Request(HTTP\MethodGet, \sprintf('v1/acl/info/%s', $id), $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntriesResponse
     */
    public function List(?QueryOptions $opts = null): ACLEntriesResponse
    {
        $r = new Request(HTTP\MethodGet, 'v1/acl/list', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ACL\ACLReplicationStatusResponse
     */
    public function Replication(?QueryOptions $opts = null): ACLReplicationStatusResponse
    {
        $r = new Request(HTTP\MethodGet, '/v1/acl/replication', $this->config, null);
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
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
