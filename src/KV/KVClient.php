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
     * @return string[]
     * @throws \Exception
     */
    public function getKeys($prefix = null)
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

        return $data;
    }

    /**
     * @param $key
     * @return \DCarbone\SimpleConsulPHP\KV\KVPair|null
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

        if (0 === count($data))
            return null;

        if (is_int(key($data)))
            $data = reset($data);

        return new KVPair($data);
    }
}