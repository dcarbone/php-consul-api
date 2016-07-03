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
use DCarbone\SimpleConsulPHP\Base\QueryParameters;

/**
 * Class KVClient
 * @package DCarbone\SimpleConsulPHP\KV
 */
class KVClient extends AbstractConsulClient
{
    /**
     * @param string $prefix Prefix to search for.  Null returns all keys.
     * @return null|string[]
     */
    public function getKeys($prefix = null)
    {
        if (null === $prefix)
        {
            $data = $this->execute('GET', 'v1/kv', new QueryParameters(['keys']));
        }
        else if (is_string($prefix))
        {
            $data = $this->execute('GET', sprintf('v1/kv/%s', $prefix), new QueryParameters(['keys']));
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
     * @param string $key Name of key to retrieve value for
     * @return KVPair|null Key Value Pair object or null if not found
     * @throws \Exception
     */
    public function getValue($key)
    {
        if (is_string($key))
        {
            $data = $this->execute('GET', sprintf('v1/kv/%s', $key));
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
     * @param KVPair $KVPair
     * @return bool
     */
    public function setValue(KVPair $KVPair)
    {
        $this->setCurlOpt(CURLOPT_POSTFIELDS, $KVPair->getEncodedValue());
        return (bool)$this->execute(
            'PUT',
            sprintf('v1/kv/%s', $KVPair->getKey())
        );

    }    

    /**
     * @param null|string $prefix
     * @return KVTree[]|KVPair[]
     */
    public function getTree($prefix = null)
    {
        $data = $this->getKeys($prefix);

        if (null === $data)
            return null;

        $treeHierarchy = array();
        foreach($data as $path)
        {
            $slashPos = strpos($path, '/');
            if (false === $slashPos)
            {
                $treeHierarchy[$path] = $this->getValue($path);
                continue;
            }

            $root = substr($path, 0, $slashPos + 1);

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
}