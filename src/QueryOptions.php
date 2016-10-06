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
use DCarbone\PHPConsulAPI\Collection\AbstractStrictCollection;

/**
 * Class QueryOptions
 * @package DCarbone\PHPConsulAPI
 */
class QueryOptions extends AbstractStrictCollection
{
    use DCarbone\PHPConsulAPI\ConsulHttpParamContainerTrait;

    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Datacenter' => null,
            'AllowStale' => null,
            'RequireConsistent' => null,
            'WaitIndex' => null,
            'WaitTime' => null,
            'Token' => null,
            'Near' => null,
        );
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return (string)$this->_storage['Datacenter'];
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
        $this->_storage['AllowStale'] = $allowStale;
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
        $this->_storage['WaitIndex'] = $waitIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getWaitTime()
    {
        return (int)$this->_storage['WaitTime'];
    }

    /**
     * @param int $waitTime
     * @return $this
     */
    public function setWaitTime($waitTime)
    {
        $this->_storage['WaitTime'] = $waitTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return (string)$this->_storage['Token'];
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
        return (string)$this->_storage['Near'];
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
        $this->_storage['RequireConsistent'] = $requireConsistent;
        return $this;
    }
}