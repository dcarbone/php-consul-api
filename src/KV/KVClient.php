<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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

use DCarbone\Go\HTTP;
use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\ValuedQueryStringsResponse;
use DCarbone\PHPConsulAPI\ValuedWriteBoolResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class KVClient
 */
class KVClient extends AbstractClient
{
    /**
     * @param string $key
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\KV\KVPairResponse
     */
    public function Get(string $key, ?QueryOptions $opts = null): KVPairResponse
    {
        $resp     = $this->_doGet(\sprintf('v1/kv/%s', $key), $opts);
        $ret      = new KVPairResponse();
        $ret->Err = $resp->Err;
        if (null !== $resp->Err) {
            return $ret;
        }

        $ret->QueryMeta = $resp->buildQueryMeta();

        $code = $resp->Response->getStatusCode();

        if (200 === $code) {
            // success response
            $dec = $this->_decodeBody($resp->Response->getBody());
            if (null !== $dec->Err) {
                $ret->Err = $dec->Err;
            } else {
                $ret->hydrateValue($dec->Decoded[0]);
            }
        } elseif (404 !== $code) {
            $ret->Err = new Error(\sprintf('%s: %s', $code, $resp->Response->getReasonPhrase()));
        }

        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Put(KVPair $p, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut(\sprintf('v1/kv/%s', $p->Key), null, $opts);
    }

    /**
     * @param string $key
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Delete(string $key, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(\sprintf('v1/kv/%s', $key), $opts);
    }

    /**
     * @param string $prefix
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\KV\KVPairsResponse
     */
    public function List(string $prefix = '', ?QueryOptions $opts = null): KVPairsResponse
    {
        $r = $this->_newGetRequest(\sprintf('v1/kv/%s', $prefix), $opts);
        $r->params->set('recurse', '');
        $ret  = new KVPairsResponse();
        $resp = $this->_requireOK($this->_do($r));
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $prefix
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedQueryStringsResponse
     */
    public function Keys(string $prefix = '', ?QueryOptions $opts = null): ValuedQueryStringsResponse
    {
        $r = $this->_newGetRequest(\sprintf('v1/kv/%s', $prefix), $opts);
        $r->params->set('keys', '');
        $ret  = new ValuedQueryStringsResponse();
        $resp = $this->_requireOK($this->_do($r));
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedWriteBoolResponse
     */
    public function CAS(KVPair $p, ?WriteOptions $opts = null): ValuedWriteBoolResponse
    {
        $r = $this->_newPutRequest(\sprintf('v1/kv/%s', $p->Key), $p->Value, $opts);
        $r->params->set('cas', (string)$p->ModifyIndex);
        if (0 !== $p->Flags) {
            $r->params->set('flags', (string)$p->Flags);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ValuedWriteBoolResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Acquire(KVPair $p, ?WriteOptions $opts = null): WriteResponse
    {
        $r = $this->_newPutRequest(\sprintf('v1/kv/%s', $p->Key), $p->Value, $opts);
        $r->params->set('acquire', $p->Session);
        if (0 !== $p->Flags) {
            $r->params->set('flags', (string)$p->Flags);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new WriteResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedWriteBoolResponse
     */
    public function DeleteCAS(KVPair $p, ?WriteOptions $opts = null): ValuedWriteBoolResponse
    {
        $r                = $this->_newDeleteRequest(\sprintf('v1/kv/%s', \ltrim($p->Key, '/')), $opts);
        $r->params['cas'] = (string)$p->ModifyIndex;
        $resp             = $this->_requireOK($this->_do($r));
        $ret              = new ValuedWriteBoolResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Release(KVPair $p, ?WriteOptions $opts = null): WriteResponse
    {
        $r = $this->_newPutRequest(\sprintf('v1/kv/%s', $p->Key), $p->Value, $opts);
        $r->params->set('release', $p->Session);
        if (0 !== $p->Flags) {
            $r->params->set('flags', (string)$p->Flags);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new WriteResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $prefix
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function DeleteTree(string $prefix, ?WriteOptions $opts = null): WriteResponse
    {
        $r                    = $this->_newDeleteRequest(\sprintf('v1/kv/%s', $prefix), $opts);
        $r->params['recurse'] = '';
        $resp                 = $this->_requireOK($this->_do($r));
        $ret                  = new WriteResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVTxnOps $txn
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\KV\KVTxnAPIResponse
     */
    public function Txn(KVTxnOps $txn, ?QueryOptions $opts = null): KVTxnAPIResponse
    {
        $txnOps = new KVTxnOps();
        foreach ($txn as $op) {
            $txnOps->append(clone $op);
        }

        $ret = new KVTxnAPIResponse();

        $resp = $this->_doPut('v1/txn', $txnOps, $opts);
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
            $internal           = new TxnResponse($dec->Decoded);
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

    /**
     * @param string|null $prefix
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array(
     * @var \DCarbone\PHPConsulAPI\KV\KVPair[]|\DCarbone\PHPConsulAPI\KV\KVTree[]|null array of trees, values, or null on error
     * @var \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Tree(string $prefix = '', ?QueryOptions $opts = null): array
    {
        [$valueList, $_, $err] = $this->List($prefix, $opts);

        if (null !== $err) {
            return [null, $err];
        }

        $treeHierarchy = [];
        foreach ($valueList as $path => $kvp) {
            $slashPos = \strpos($path, '/');
            if (false === $slashPos) {
                $treeHierarchy[$path] = $kvp;
                continue;
            }

            $root = \substr($path, 0, $slashPos + 1);

            if (!isset($treeHierarchy[$root])) {
                $treeHierarchy[$root] = new KVTree($root);
            }

            if ('/' === \substr($path, -1)) {
                $_path = '';
                foreach (\explode('/', $prefix) as $part) {
                    if ('' === $part) {
                        continue;
                    }

                    $_path .= "{$part}/";

                    if ($root === $_path) {
                        continue;
                    }

                    if (!isset($treeHierarchy[$root][$_path])) {
                        $treeHierarchy[$root][$_path] = new KVTree($_path);
                    }
                }
            } else {
                $kvPrefix = \substr($path, 0, \strrpos($path, '/') + 1);
                $_path    = '';
                foreach (\explode('/', $kvPrefix) as $part) {
                    if ('' === $part) {
                        continue;
                    }

                    $_path .= "{$part}/";

                    if ($root === $_path) {
                        continue;
                    }

                    if (!isset($treeHierarchy[$root][$_path])) {
                        $treeHierarchy[$root][$_path] = new KVTree($_path);
                    }
                }

                $treeHierarchy[$root][$path] = $kvp;
            }
        }
        return [$treeHierarchy, null];
    }
}
