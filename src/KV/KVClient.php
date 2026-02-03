<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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
use DCarbone\PHPConsulAPI\PHPLib\Response\ValuedQueryStringsResponse;
use DCarbone\PHPConsulAPI\PHPLib\Response\ValuedWriteBoolResponse;
use DCarbone\PHPConsulAPI\Txn\KVTxnAPIResponse;
use DCarbone\PHPConsulAPI\Txn\KVTxnOps;
use DCarbone\PHPConsulAPI\Txn\KVTxnResponse;
use DCarbone\PHPConsulAPI\Txn\TxnOp;
use DCarbone\PHPConsulAPI\Txn\TxnResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\PHPLib\Response\WriteResponse;

class KVClient extends AbstractClient
{
    public function Get(string $key, null|QueryOptions $opts = null): KVPairResponse
    {
        $resp = $this->_doGet(sprintf('v1/kv/%s', $key), $opts);
        $ret = new KVPairResponse();
        $ret->Err = $resp->Err;
        if (null !== $resp->Err) {
            return $ret;
        }

        $ret->QueryMeta = $resp->buildQueryMeta();

        $code = $resp->Response->getStatusCode();

        if (HTTP\StatusOK === $code) {
            // success response
            $dec = $this->_decodeBody($resp->Response->getBody());
            if (null !== $dec->Err) {
                $ret->Err = $dec->Err;
            } else {
                $ret->unmarshalValue($dec->Decoded[0]);
            }
        } elseif (HTTP\StatusNotFound !== $code) {
            $ret->Err = new Error(sprintf('%s: %s', $code, $resp->Response->getReasonPhrase()));
        }

        return $ret;
    }

    public function Put(KVPair $p, null|WriteOptions $opts = null): WriteResponse
    {
        $r = $this->_newPutRequest(sprintf('v1/kv/%s', $p->Key), base64_encode($p->Value), $opts);
        if (0 !== $p->Flags) {
            $r->params->set('flags', (string)$p->Flags);
        }
        $r->header->set('Content-Type', 'application/octet-stream');
        $resp = $this->_requireOK($this->_do($r));
        $ret = new WriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Delete(string $key, null|WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(sprintf('v1/kv/%s', $key), $opts);
    }

    public function List(string $prefix = '', null|QueryOptions $opts = null): KVPairsResponse
    {
        $r = $this->_newGetRequest(sprintf('v1/kv/%s', $prefix), $opts);
        $r->params->set('recurse', '');
        $ret = new KVPairsResponse();
        $resp = $this->_requireOK($this->_do($r));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Keys(string $prefix = '', null|QueryOptions $opts = null): ValuedQueryStringsResponse
    {
        $r = $this->_newGetRequest(sprintf('v1/kv/%s', $prefix), $opts);
        $r->params->set('keys', '');
        $ret = new ValuedQueryStringsResponse();
        $resp = $this->_requireOK($this->_do($r));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function CAS(KVPair $p, null|WriteOptions $opts = null): ValuedWriteBoolResponse
    {
        $r = $this->_newPutRequest(sprintf('v1/kv/%s', $p->Key), $p->Value, $opts);
        $r->params->set('cas', (string)$p->ModifyIndex);
        if (0 !== $p->Flags) {
            $r->params->set('flags', (string)$p->Flags);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret = new ValuedWriteBoolResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Acquire(KVPair $p, null|WriteOptions $opts = null): WriteResponse
    {
        $r = $this->_newPutRequest(sprintf('v1/kv/%s', $p->Key), $p->Value, $opts);
        $r->params->set('acquire', $p->Session);
        if (0 !== $p->Flags) {
            $r->params->set('flags', (string)$p->Flags);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret = new WriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function DeleteCAS(KVPair $p, null|WriteOptions $opts = null): ValuedWriteBoolResponse
    {
        $r = $this->_newDeleteRequest(sprintf('v1/kv/%s', ltrim($p->Key, '/')), $opts);
        $r->params->set('cas', (string)$p->ModifyIndex);
        $resp = $this->_requireOK($this->_do($r));
        $ret = new ValuedWriteBoolResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Release(KVPair $p, null|WriteOptions $opts = null): WriteResponse
    {
        $r = $this->_newPutRequest(sprintf('v1/kv/%s', $p->Key), $p->Value, $opts);
        $r->params->set('release', $p->Session);
        if (0 !== $p->Flags) {
            $r->params->set('flags', (string)$p->Flags);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new WriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function DeleteTree(string $prefix, null|WriteOptions $opts = null): WriteResponse
    {
        $r = $this->_newDeleteRequest(sprintf('v1/kv/%s', $prefix), $opts);
        $r->params->set('recurse', '');
        $resp = $this->_requireOK($this->_do($r));
        $ret = new WriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param array<\DCarbone\PHPConsulAPI\Txn\TxnOp> $txn
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Txn\KVTxnAPIResponse
     */
    public function Txn(array $txn, null|QueryOptions $opts = null): KVTxnAPIResponse
    {
        foreach ($txn as $op) {
            if (!($op instanceof TxnOp)) {
                throw new \InvalidArgumentException(sprintf('$txn must be array of %s, saw %s', TxnOp::class, gettype($op)));
            }
        }

        $ret = new KVTxnAPIResponse();

        $resp = $this->_doPut('v1/txn', $txn, $opts);
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }

        $ret->QueryMeta = $resp->buildQueryMeta();
        $code           = $resp->Response->getStatusCode();
        $ret->OK        = HTTP\StatusOK === $code;

        if (200 === $code || 409 === $code) {
            $dec = $this->_decodeBody($resp->Response->getBody());
            if (null !== $dec->Err) {
                $ret->OK  = false;
                $ret->Err = $dec->Err;
                return $ret;
            }
            $ret->OK = true;
            // TODO: Maybe go straight to actual response?  What is the benefit of this...
            $internal = new TxnResponse($dec->Decoded);
            $ret->KVTxnResponse = new KVTxnResponse(['Errors' => $internal->Errors, 'Results' => $internal->Results]);
            return $ret;
        }

        if ('' === ($body = $resp->Response->getBody()->getContents())) {
            $ret->Err = new Error('Unable to read response');
            return $ret;
        }

        $ret->Err = new Error('Failed request: ' . $body);
        return $ret;
    }
}
