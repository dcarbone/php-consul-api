<?php namespace DCarbone\PHPConsulAPI\KV;

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

use DCarbone\PHPConsulAPI\AbstractConsulClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\Hydrator;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class KVClient
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVClient extends AbstractConsulClient
{
    /**
     * @param string $key Name of key to retrieve value for
     * @param QueryOptions $queryOptions
     * @return array(
     *  @type KVPair|null kv object or null on error
     *  @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata object or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function get($key, QueryOptions $queryOptions = null)
    {
        if (!is_string($key))
        {
            return [null, null, new Error(sprintf(
                '%s::get - Key expected to be string, %s seen.',
                get_class($this),
                gettype($key)
            ))];
        }

        $r = new Request('get', sprintf('v1/kv/%s', rawurlencode($key)), $this->_Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        $data = $data[0];

        return [Hydrator::KVPair($data), $qm, null];
    }

    /**
     * @param string $prefix
     * @param QueryOptions|null $queryOptions
     * @return array(
     *  @type KVPair[]|null array of KVPair objects under specified prefix
     *  @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function valueList($prefix, QueryOptions $queryOptions = null)
    {
        if (!is_string($prefix) || '' === $prefix)
        {
            return [null, null, new Error(sprintf(
                '%s::valueList - Prefix expected to be non-empty string, "%s" seen.',
                get_class($this),
                is_string($prefix) ? $prefix : gettype($prefix)
            ))];
        }

        $r = new Request('get', sprintf('v1/kv/%s', rawurlencode($prefix)), $this->_Config);
        $r->setQueryOptions($queryOptions);
        $r->params()->set('recurse', '');

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        $kvPairs = array();
        foreach($data as $v)
        {
            $kvp = Hydrator::KVPair($v);
            $kvPairs[$kvp->Key] = $kvp;
        }

        return [$kvPairs, $qm, null];
    }

    /**
     * @param string $prefix Prefix to search for.  Null returns all keys.
     * @param QueryOptions $queryOptions
     * @return array(
     *  @type string[]|null list of keys
     *  @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function keys($prefix = null, QueryOptions $queryOptions = null)
    {
        if (null !== $prefix && !is_string($prefix))
        {
            return [null, null, new Error(sprintf(
                '%s::keys - Prefix expected to be empty or string, %s seen.',
                get_class($this),
                gettype($prefix)
            ))];
        }

        if (null === $prefix)
            $r = new Request('get', 'v1/kv/', $this->_Config);
        else
            $r = new Request('get', sprintf('v1/kv/%s', rawurlencode($prefix)), $this->_Config);

        $r->setQueryOptions($queryOptions);
        $r->params()->set('keys', true);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        return [$data, $qm, $err];
    }

    /**
     * @param KVPair $KVPair
     * @param WriteOptions $writeOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function put(KVPair $KVPair, WriteOptions $writeOptions = null)
    {
        $r = new Request('put', sprintf('v1/kv/%s', rawurlencode($KVPair->getKey())), $this->_Config);
        $r->setWriteOptions($writeOptions);
        $r->setBody($KVPair);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        return [$wm, $err];
    }

    /**
     * @param string $key
     * @param WriteOptions|null $writeOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\WriteMeta metadata about write
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function delete($key, WriteOptions $writeOptions = null)
    {
        $r = new Request('delete', sprintf('v1/kv/%s', rawurlencode($key)), $this->_Config);
        $r->setWriteOptions($writeOptions);

        list ($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        return [$wm, $err];
    }

    /**
     * @param null|string $prefix
     * @param QueryOptions $queryOptions
     * @return array(
     *  @type KVPair[]|KVTree[]|null array of trees, values, or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function tree($prefix = null, QueryOptions $queryOptions = null)
    {
        list($keys, $_, $err) = $this->keys($prefix, $queryOptions);

        if (null !== $err)
            return [null, $err];

        $treeHierarchy = array();
        foreach($keys as $path)
        {
            $slashPos = strpos($path, '/');
            if (false === $slashPos)
            {
                list($kv, $_, $err) = $this->get($path, $queryOptions);
                if (null !== $err)
                    return [null, $err];

                $treeHierarchy[$path] = $kv;
                continue;
            }

            $root = substr($path, 0, $slashPos + 1);

            if (!isset($treeHierarchy[$root]))
                $treeHierarchy[$root] = new KVTree($root);

            // We're still in the path definition...
            if ('/' === substr($path, -1))
            {
                $treeHierarchy[$root][$path] = new KVTree($path);
            }
            // We've arrived at an actual key
            else
            {
                list($kv, $_, $err) = $this->get($path, $queryOptions);
                if (null !== $err)
                    return [null, $err];

                $treeHierarchy[$root][$path] = $kv;
            }
        }
        return [$treeHierarchy, null];
    }
}