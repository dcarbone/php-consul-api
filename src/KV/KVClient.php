<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class KVClient
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVClient extends AbstractClient
{
    /**
     * @param string $key Name of key to retrieve value for
     * @param \DCarbone\PHPConsulAPI\QueryOptions $options
     * @return array(
     * @type KVPair|null kv object or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata object or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Get(string $key, QueryOptions $options = null): array
    {
        $r = new Request('GET', sprintf('v1/kv/%s', $key), $this->config);
        $r->setQueryOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->doRequest($r);
        if (null !== $err) {
            return [null, null, $err];
        }

        $code = $response->getStatusCode();

        if (200 === $code) {
            [$data, $err] = $this->decodeBody($response->getBody());

            if (null !== $err) {
                return [null, null, $err];
            }

            $qm = $this->buildQueryMeta($duration, $response, $r->getUri());
            return [new KVPair($data[0], true), $qm, null];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        if (404 === $code) {
            return [null, $qm, null];
        }

        return [null, $qm, new Error(sprintf('%s: %s', $response->getStatusCode(), $response->getReasonPhrase()))];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Put(KVPair $p, WriteOptions $options = null): array
    {
        $r = new Request('PUT', sprintf('v1/kv/%s', $p->Key), $this->config, $p->Value);
        $r->setWriteOptions($options);
        if (0 !== $p->Flags) {
            $r->Params->set('flags', (string)$p->Flags);
        }

        [$duration, $_, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @param string $key
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta metadata about write
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Delete(string $key, WriteOptions $options = null): array
    {
        $r = new Request('DELETE', sprintf('v1/kv/%s', $key), $this->config);
        $r->setWriteOptions($options);

        list ($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @param string $prefix
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\KV\KVPairs|null array of KVPair objects under specified prefix
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function List(string $prefix = '', QueryOptions $options = null): array
    {
        $r = new Request('GET', sprintf('v1/kv/%s', $prefix), $this->config);
        $r->setQueryOptions($options);
        $r->Params->set('recurse', '');

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        return [new KVPairs($data), $qm, null];
    }

    /**
     * @param string $prefix Prefix to search for.  Null returns all keys.
     * @param \DCarbone\PHPConsulAPI\QueryOptions $options
     * @return array(
     * @type string[]|null list of keys
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Keys(string $prefix = '', QueryOptions $options = null): array
    {
        $r = new Request('GET', sprintf('v1/kv/%s', $prefix), $this->config);
        $r->setQueryOptions($options);
        $r->Params->set('keys', '');

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        return [$data, $this->buildQueryMeta($duration, $response, $r->getUri()), $err];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions $options
     * @return array(
     * @type bool whether the operation succeeded or not
     * @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function CAS(KVPair $p, WriteOptions $options = null): array
    {
        $r = new Request('PUT', sprintf('v1/kv/%s', $p->Key), $this->config, $p->Value);
        $r->setWriteOptions($options);
        $r->Params->set('cas', (string)$p->ModifyIndex);
        if (0 !== $p->Flags) {
            $r->Params->set('flags', (string)$p->Flags);
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [0 === strpos($response->getBody()->getContents(), 'true'), $this->buildWriteMeta($duration), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Acquire(KVPair $p, WriteOptions $options = null): array
    {
        $r = new Request('PUT', sprintf('v1/kv/%s', $p->Key), $this->config, $p->Value);
        $r->setWriteOptions($options);
        $r->Params->set('acquire', $p->Session);
        if (0 !== $p->Flags) {
            $r->Params->set('flags', (string)$p->Flags);
        }

        [$duration, $_, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type bool
     * @type \DCarbone\PHPConsulAPI\WriteMeta
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function DeleteCAS(KVPair $p, WriteOptions $options = null): array
    {
        $r = new Request('DELETE', sprintf('v1/kv/%s', ltrim($p->Key, "/")), $this->config);
        $r->setWriteOptions($options);
        $r->Params['cas'] = (string)$p->ModifyIndex;

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        return [0 === strpos($response->getBody()->getContents(), 'true'), $this->buildWriteMeta($duration), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $p
     * @param \DCarbone\PHPConsulAPI\WriteOptions $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Release(KVPair $p, WriteOptions $options = null): array
    {
        $r = new Request('PUT', sprintf('v1/kv/%s', $p->Key), $this->config, $p->Value);
        $r->setWriteOptions($options);
        $r->Params->set('release', $p->Session);
        if (0 !== $p->Flags) {
            $r->Params->set('flags', (string)$p->Flags);
        }

        [$duration, $_, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @param string $prefix
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function DeleteTree(string $prefix, QueryOptions $options = null): array
    {
        $r = new Request('DELETE', sprintf('v1/kv/%s', $prefix), $this->config);
        $r->Params['recurse'] = '';
        $r->setWriteOptions($options);

        [$duration, $_, $err] = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVTxnOps $txn
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type bool
     * @type \DCarbone\PHPConsulAPI\KV\KVTxnResponse|null
     * @type \DCarbone\PHPConsulAPI\QueryOptions|null
     * @type \DCarbone\PHPConsulAPI\Error|null
     * )
     */
    public function Txn(KVTxnOps $txn, QueryOptions $options = null): array
    {
        $ops = new KVTxnOps();
        foreach ($txn as $op) {
            $ops->append(clone $op);
        }

        $r = new Request('PUT', 'v1/txn', $this->config, $ops);
        $r->setQueryOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->doRequest($r);
        if (null !== $err) {
            return [false, null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        $code = $response->getStatusCode();
        if (200 === $code || 409 === $code) {
            [$data, $err] = $this->decodeBody($response->getBody());
            if (null !== $err) {
                return [false, null, null, $err];
            }

            // TODO: Maybe go straight to actual response?  What is the benefit of this...
            $internal = new TxnResponse($data);

            $resp = new KVTxnResponse(['Errors' => $internal->Errors, 'Results' => $internal->Results]);
            return [200 === $code, $resp, $qm, null];
        }

        if ('' === ($body = $response->getBody()->getContents())) {
            return [false, null, null, new Error('Unable to read response')];
        }

        return [false, null, null, new Error('Failed request: ' . $body)];
    }

    /**
     * @param null|string $prefix
     * @param \DCarbone\PHPConsulAPI\QueryOptions $options
     * @return array(
     * @type KVPair[]|KVTree[]|null array of trees, values, or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Tree(string $prefix = '', QueryOptions $options = null): array
    {
        [$valueList, $_, $err] = $this->List($prefix, $options);

        if (null !== $err) {
            return [null, $err];
        }

        $treeHierarchy = [];
        foreach ($valueList as $path => $kvp) {
            $slashPos = strpos($path, '/');
            if (false === $slashPos) {
                $treeHierarchy[$path] = $kvp;
                continue;
            }

            $root = substr($path, 0, $slashPos + 1);

            if (!isset($treeHierarchy[$root])) {
                $treeHierarchy[$root] = new KVTree($root);
            }

            if ('/' === substr($path, -1)) {
                $_path = '';
                foreach (explode('/', $prefix) as $part) {
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
                $kvPrefix = substr($path, 0, strrpos($path, '/') + 1);
                $_path = '';
                foreach (explode('/', $kvPrefix) as $part) {
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
