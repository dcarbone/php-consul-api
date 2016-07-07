<?php namespace DCarbone\PHPConsulAPI;

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

/**
 * Class QueryOptions
 * @package DCarbone\PHPConsulAPI\Query
 */
class QueryOptions extends AbstractCollection
{
    /**
     * @return bool
     */
    public function getKeys()
    {
        return (bool)$this['keys'];
    }

    /**
     * @param bool $keys
     * @return $this
     */
    public function setKeys($keys)
    {
        $this['keys'] = (bool)$keys;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return $this['Datacenter'];
    }

    /**
     * @param string $datacenter
     * @return $this
     */
    public function setDatacenter($datacenter)
    {
        $this['Datacenter'] = $datacenter;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowStale()
    {
        return (bool)$this['AllowStale'];
    }

    /**
     * @param bool $allowStale
     * @return $this
     */
    public function setAllowStale($allowStale)
    {
        $this['AllowStale'] = (bool)$allowStale;
        return $this;
    }

    /**
     * @return int
     */
    public function getWaitIndex()
    {
        return (int)$this['WaitIndex'];
    }

    /**
     * @param int $waitIndex
     * @return $this
     */
    public function setWaitIndex($waitIndex)
    {
        $this['WaitIndex'] = (int)$waitIndex;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this['Token'];
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this['Token'] = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getNear()
    {
        return $this['Near'];
    }

    /**
     * @param string $near
     * @return $this
     */
    public function setNear($near)
    {
        $this['Near'] = $near;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRequireConsistent()
    {
        return (bool)$this['RequireConsistent'];
    }

    /**
     * @param string $requireConsistent
     * @return $this
     */
    public function setRequireConsistent($requireConsistent)
    {
        $this['RequireConsistent'] = (bool)$requireConsistent;
        return $this;
    }

    /**
     * @return array
     */
    public function buildHttpQueryArray()
    {
        $params = array();
        foreach($this as $k=>$v)
        {
            // "keys" is a special one who's actual value seems to be ignored...
            if ('keys' === $k)
            {
                if (true === $v)
                    $params['keys'] = true;

                continue;
            }

            if (null !== $v)
            {
                $value = $v;

                switch($k)
                {
                    case 'Datacenter':
                        $key = 'dc';
                        break;
                    case 'AllowStale':
                        $key = 'stale';
                        break;
                    case 'RequireConsistent':
                        $key = 'consistent';
                        break;
                    case 'WaitIndex':
                        $key = 'index';
                        break;
                    case 'WaitTime':
                        $key = 'wait';
                        break;

                    default:
                        $key = strtolower($k);
                }

                $params[$key] = $value;
            }
        }

        return $params;
    }

    /**
     * @return string
     */
    public function buildHttpQueryString()
    {
        return (string)$this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return http_build_query($this->buildHttpQueryArray());
    }
}