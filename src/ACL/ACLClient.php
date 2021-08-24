<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\KV\ACLTokenQueryResponse;
use DCarbone\PHPConsulAPI\KV\ACLTokenWriteResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
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
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntriesResponse
     */
    public function Info(string $id, ?QueryOptions $opts = null): ACLEntriesResponse
    {
        $resp = $this->_requireOK($this->_doGet(\sprintf('v1/acl/info/%s', $id), $opts));
        $ret  = new ACLEntriesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntriesResponse
     */
    public function List(?QueryOptions $opts = null): ACLEntriesResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/acl/list', $opts));
        $ret  = new ACLEntriesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ACL\ACLReplicationStatusResponse
     */
    public function Replication(?QueryOptions $opts = null): ACLReplicationStatusResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/replication', $opts));
        $ret  = new ACLReplicationStatusResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLToken $token
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\KV\ACLTokenWriteResponse
     */
    public function TokenCreate(ACLToken $token, ?WriteOptions $opts = null): ACLTokenWriteResponse
    {
        $resp = $this->_requireOK($this->_doPut('/v1/acl/token', $token, $opts));
        $ret  = new ACLTokenWriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ACL\ACLToken $token
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\KV\ACLTokenWriteResponse
     */
    public function TokenUpdate(ACLToken $token, ?WriteOptions $opts = null): ACLTokenWriteResponse
    {
        $ret = new ACLTokenWriteResponse();
        if ('' === $token->AccessorID) {
            $ret->Err = new Error('must specify AccessorID for Token Updating');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut(\sprintf('/v1/acl/token/%s', $token->AccessorID), $token, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $tokenID
     * @param string $description
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\KV\ACLTokenWriteResponse
     */
    public function TokenClone(string $tokenID, string $description, ?WriteOptions $opts = null): ACLTokenWriteResponse
    {
        $ret = new ACLTokenWriteResponse();
        if ('' === $tokenID) {
            $ret->Err = new Error('must specify tokenID for Token Cloning');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut(\sprintf('/v1/acl/token/%s/clone', $tokenID), ['description' => $description], $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $tokenID
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function TokenDelete(string $tokenID, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(\sprintf('/v1/acl/token/%s', $tokenID), $opts);
    }

    /**
     * @param string $tokenID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\KV\ACLTokenQueryResponse
     */
    public function TokenRead(string $tokenID, ?QueryOptions $opts = null): ACLTokenQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet(\sprintf('/v1/acl/token/%s', $tokenID), $opts));
        $ret  = new ACLTokenQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\KV\ACLTokenQueryResponse
     */
    public function TokenReadSelf(?QueryOptions $opts = null): ACLTokenQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/token/self', $opts));
        $ret  = new ACLTokenQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ACL\ACLTokenListEntryQueryResponse
     */
    public function TokenList(?QueryOptions $opts = null): ACLTokenListEntryQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/tokens', $opts));
        $ret  = new ACLTokenListEntryQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }
}
