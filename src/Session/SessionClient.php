<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Session;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

class SessionClient extends AbstractClient
{
    public function CreateNoChecks(?SessionEntry $sessionEntry = null, null|WriteOptions $opts = null): ValuedWriteStringResponse
    {
        if (null === $sessionEntry) {
            $body = new SessionEntry();
        } else {
            $body = clone $sessionEntry;
        }

        $body->Checks        = [];
        $body->NodeChecks    = [];
        $body->ServiceChecks = [];

        return $this->_create('v1/session/create', $body, $opts);
    }

    public function Create(?SessionEntry $sessionEntry = null, null|WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_create('v1/session/create', $sessionEntry, $opts);
    }

    public function Destroy(string $id, null|WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut(sprintf('v1/session/destroy/%s', $id), null, $opts);
    }

    public function Renew(string $id, null|WriteOptions $opts = null): SessionEntriesWriteResponse
    {
        $ret = new SessionEntriesWriteResponse();

        $resp = $this->_doPut(sprintf('v1/session/renew/%s', $id), null, $opts);
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }

        $ret->WriteMeta = $resp->buildWriteMeta();

        switch ($code = $resp->Response->getStatusCode()) {
            case HTTP\StatusNotFound:
                break;
            case HTTP\StatusOK:
                $this->_unmarshalResponse($resp, $ret);
                break;
            default:
                $ret->Err = new Error(
                    sprintf(
                        '%s::renew - Unexpected response code %d.  Reason: %s',
                        static::class,
                        $code,
                        $resp->Response->getReasonPhrase()
                    )
                );
        }

        return $ret;
    }

    public function Info(string $id, null|QueryOptions $opts = null): SessionEntriesQueryResponse
    {
        return $this->_get(sprintf('v1/session/info/%s', $id), $opts);
    }

    public function Node(string $node, null|QueryOptions $opts = null): SessionEntriesQueryResponse
    {
        return $this->_get(sprintf('v1/session/node/%s', $node), $opts);
    }

    public function List(null|QueryOptions $opts = null): SessionEntriesQueryResponse
    {
        return $this->_get('v1/session/list', $opts);
    }

    private function _get(string $path, null|QueryOptions $opts): SessionEntriesQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet($path, $opts));
        $ret  = new SessionEntriesQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    private function _create(string $path, SessionEntry $entry, null|WriteOptions $opts): ValuedWriteStringResponse
    {
        $resp = $this->_requireOK($this->_doPut($path, $entry->_toAPIPayload(), $opts));
        $ret  = new ValuedWriteStringResponse();

        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }

        $ret->WriteMeta = $resp->buildWriteMeta();

        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            $ret->Err = $dec->Err;
            return $ret;
        }

        $ret->Value = $dec->Decoded['ID'] ?? '';
        return $ret;
    }
}
