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

use DCarbone\SimpleConsulPHP\Base\AbstractConsulClient;

/**
 * Class KVClient
 * @package DCarbone\SimpleConsulPHP\KV
 */
class KVClient extends AbstractConsulClient
{
    /**
     * @param string $prefix Prefix to search for.  Null returns all keys.
     * @param bool $expand If false, returns array of key names.  If true, expands into nested objects
     * @return null|string[]|KVTree[]|KVPair[]
     */
    public function getKeys($prefix = null, $expand = false)
    {
        if (null === $prefix)
        {
            $data = $this->execute('v1/kv/?keys');
        }
        else if (is_string($prefix))
        {
            $data = $this->execute(sprintf('v1/kv/%s/?keys', trim(trim($prefix), "/")));
        }
        else
        {
            throw new \InvalidArgumentException(sprintf(
                '%s::getKeys - Prefix expected to be empty or string, %s seen.',
                get_class($this),
                gettype($prefix)
            ));
        }

        if (null === $data || false === $expand)
            return $data;

        $treeHierarchy = array();
        foreach($data as $path)
        {
            if (false === strpos($path, '/'))
            {
                $treeHierarchy[$path] = $this->getValue($path);
                continue;
            }

            $root = strstr($path, '/', true);

            // We're still in the path definition...
            if ('/' === substr($path, -1))
            {
                if (!isset($treeHierarchy[$root]))
                    $treeHierarchy[$root] = new KVTree($root);

                $treeHierarchy[$root][$path] = new KVTree($path);
            }
            // We've arrived at an actual key
            else
            {
                $treeHierarchy[$root][$path] = $this->getValue($path);
            }
        }

        return $treeHierarchy;
    }

    /**
     * @param string $key Name of key to retrieve value for
     * @return \DCarbone\SimpleConsulPHP\KV\KVPair|null Key Value Pair object or null if not found
     * @throws \Exception
     */
    public function getValue($key)
    {
        if (is_string($key))
        {
            $data = $this->execute(sprintf('v1/kv/%s', $key));
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
        
        if (is_int(key($data)))
            $data = reset($data);
        
        return new KVPair($data, $this);
    }

    /**
     * @return KVTransaction
     */
    public function newTransaction()
    {
        return new KVTransaction($this);
    }
}