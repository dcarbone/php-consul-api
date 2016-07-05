<?php namespace DCarbone\SimpleConsulPHP;

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
 * @package DCarbone\SimpleConsulPHP\Query
 */
class QueryOptions extends AbstractDefinedCollection
{
    /** @var array */
    protected $_storage = array(
        'keys' => false,
        'Datacenter' => null,
        'AllowStale' => false,
        'WaitIndex' => null,
        'WaitTime' => null,
        'Token' => null,
        'Near' => null,
        'RequireConsistent' => null,
    );

    /**
     * @return bool
     */
    public function getKeys()
    {
        return (bool)$this->_storage['keys'];
    }

    /**
     * @param bool $keys
     * @return $this
     */
    public function setKeys($keys)
    {
        $this->_storage['keys'] = (bool)$keys;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return $this->_storage['Datacenter'];
    }

    /**
     * @param string $datacenter
     * @return $this
     */
    public function setDatacenter($datacenter)
    {
        $this->_storage['Datacenter'] = $datacenter;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowStale()
    {
        return (bool)$this->_storage['AllowStale'];
    }

    /**
     * @param bool $allowStale
     * @return $this
     */
    public function setAllowStale($allowStale)
    {
        $this->_storage['AllowStale'] = (bool)$allowStale;
        return $this;
    }

    /**
     * @return int
     */
    public function getWaitIndex()
    {
        return (int)$this->_storage['WaitIndex'];
    }

    /**
     * @param int $waitIndex
     * @return $this
     */
    public function setWaitIndex($waitIndex)
    {
        $this->_storage['WaitIndex'] = (int)$waitIndex;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->_storage['Token'];
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->_storage['Token'] = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getNear()
    {
        return $this->_storage['Near'];
    }

    /**
     * @param string $near
     * @return $this
     */
    public function setNear($near)
    {
        $this->_storage['Near'] = $near;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRequireConsistent()
    {
        return (bool)$this->_storage['RequireConsistent'];
    }

    /**
     * @param string $requireConsistent
     * @return $this
     */
    public function setRequireConsistent($requireConsistent)
    {
        $this->_storage['RequireConsistent'] = (bool)$requireConsistent;
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
            if ('keys' === $k)
            {
                if (true === $v)
                    $params['keys'] = true;

                continue;
            }

            if (null !== $v)
            {
                $key = null;
                $value = $v;

                switch($k)
                {
                    case 'keys':
                        $key = 'keys';
                        break;
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
                    case 'Token':
                        $key = 'token';
                        break;
                    case 'Near':
                        $key = 'near';
                        break;

                    default:
                        continue 2;
                }

                if (null === $key)
                    continue;

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