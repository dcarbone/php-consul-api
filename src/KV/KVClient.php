<?php namespace DCarbone\SimpleConsulPHP\KV;

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

use DCarbone\SimpleConsulPHP\AbstractConsulClient;
use DCarbone\SimpleConsulPHP\QueryOptions;

/**
 * Class KVClient
 * @package DCarbone\SimpleConsulPHP\KV
 */
class KVClient extends AbstractConsulClient
{
    /**
     * @param string $key Name of key to retrieve value for
     * @param QueryOptions $queryOptions
     * @return KVPair|null Key Value Pair object or null if not found
     */
    public function get($key, QueryOptions $queryOptions = null)
    {
        if (is_string($key))
        {
            $data = $this->execute('GET', sprintf('v1/kv/%s', rawurlencode($key)), $queryOptions);
        }
        else
        {
            throw new \InvalidArgumentException(sprintf(
                '%s::getValue - Key expected to be string, %s seen.',
                get_class($this),
                gettype($key)
            ));
        }

        if (null === $data || 0 === count($data))
            return null;

        return new KVPair(reset($data), $this);
    }

    /**
     * @param string $prefix Prefix to search for.  Null returns all keys.
     * @param QueryOptions $queryOptions
     * @return null|\string[]
     */
    public function keys($prefix = null, QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $queryOptions->setKeys(true);

        if (null === $prefix)
        {
            $data = $this->execute('GET', 'v1/kv', $queryOptions);
        }
        else if (is_string($prefix))
        {
            $data = $this->execute('GET', sprintf('v1/kv/%s', rawurlencode($prefix)), $queryOptions);
        }
        else
        {
            throw new \InvalidArgumentException(sprintf(
                '%s::getKeys - Prefix expected to be empty or string, %s seen.',
                get_class($this),
                gettype($prefix)
            ));
        }

        return $data;
    }

    /**
     * @param KVPair $KVPair
     * @param QueryOptions $queryOptions
     * @return bool
     */
    public function put(KVPair $KVPair, QueryOptions $queryOptions = null)
    {
        return (bool)$this->execute('PUT', sprintf('v1/kv/%s', rawurlencode($KVPair->getKey())), $queryOptions, $KVPair->getValue());
    }

    /**
     * @param string $key
     * @param QueryOptions $queryOptions
     * @return bool
     */
    public function delete($key, QueryOptions $queryOptions = null)
    {
        return (bool)$this->execute('DELETE', sprintf('v1/kv/%s', rawurlencode($key)), $queryOptions);
    }

    /**
     * @param null|string $prefix
     * @param QueryOptions $queryOptions
     * @return KVPair[]|KVTree[]
     */
    public function tree($prefix = null, QueryOptions $queryOptions = null)
    {
        $data = $this->keys($prefix, $queryOptions);

        if (null === $data)
            return null;

        $treeHierarchy = array();
        foreach($data as $path)
        {
            $slashPos = strpos($path, '/');
            if (false === $slashPos)
            {
                $treeHierarchy[$path] = $this->get($path, $queryOptions);
                continue;
            }

            $root = substr($path, 0, $slashPos + 1);

            if (!isset($treeHierarchy[$root]))
                $treeHierarchy[$root] = new KVTree($root);

            // We're still in the path definition...
            if ('/' === substr($path, -1))
                $treeHierarchy[$root][$path] = new KVTree($path);
            // We've arrived at an actual key
            else
                $treeHierarchy[$root][$path] = $this->get($path);
        }
        return $treeHierarchy;
    }
}