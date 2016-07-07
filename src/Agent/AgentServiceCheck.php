<?php namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\AbstractDefinedStrictCollection;

/**
 * Class AgentServiceCheck
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentServiceCheck extends AbstractDefinedStrictCollection
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Script' => null,
            'DockerContainerID' => null,
            'Shell' => null,
            'Interval' => null,
            'Timeout' => null,
            'TTL' => null,
            'HTTP' => null,
            'TCP' => null,
            'Status' => null
        );
    }

    /**
     * @return string
     */
    public function getScript()
    {
        return $this->_storage['Script'];
    }

    /**
     * @param string $script
     * @return $this
     */
    public function setScript($script)
    {
        $this->_storage['Script'] = $script;
        return $this;
    }

    /**
     * @return string
     */
    public function getDockerContainerID()
    {
        return $this->_storage['DockerContainerID'];
    }

    /**
     * @param string $dockerContainerID
     * @return $this
     */
    public function setDockerContainerID($dockerContainerID)
    {
        $this->_storage['DockerContainerID'] = $dockerContainerID;
        return $this;
    }

    /**
     * @return string
     */
    public function getShell()
    {
        return $this->_storage['Shell'];
    }

    /**
     * @param string $shell
     * @return $this
     */
    public function setShell($shell)
    {
        $this->_storage['Shell'] = $shell;
        return $this;
    }

    /**
     * @return string
     */
    public function getInterval()
    {
        return $this->_storage['Interval'];
    }

    /**
     * @param string $interval
     * @return $this
     */
    public function setInterval($interval)
    {
        $this->_storage['Interval'] = $interval;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeout()
    {
        return $this->_storage['Timeout'];
    }

    /**
     * @param string $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->_storage['Timeout'] = $timeout;
        return $this;
    }

    /**
     * @return string
     */
    public function getTTL()
    {
        return $this->_storage['TTL'];
    }

    /**
     * @param string $ttl
     * @return $this
     */
    public function setTTL($ttl)
    {
        $this->_storage['TTL'] = $ttl;
        return $this;
    }

    /**
     * @return string
     */
    public function getHTTP()
    {
        return $this->_storage['HTTP'];
    }

    /**
     * @param string $http
     * @return $this
     */
    public function setHTTP($http)
    {
        $this->_storage['HTTP'] = $http;
        return $this;
    }

    /**
     * @return string
     */
    public function getTCP()
    {
        return $this->_storage['TCP'];
    }

    /**
     * @param string $tcp
     * @return $this
     */
    public function setTCP($tcp)
    {
        $this->_storage['TCP'] = $tcp;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->_storage['Status'];
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->_storage['Status'] = $status;
        return $this;
    }
}