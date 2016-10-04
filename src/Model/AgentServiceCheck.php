<?php namespace DCarbone\PHPConsulAPI\Model;

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
 * Class AgentServiceCheck
 * @package DCarbone\PHPConsulAPI\Model
 */
class AgentServiceCheck extends AbstractModel
{
    /** @var string */
    public $Script = '';
    /** @var string */
    public $DockerContainerID = '';
    /** @var string */
    public $Shell = '';
    /** @var string */
    public $Interval = '';
    /** @var string */
    public $Timeout = '';
    /** @var string */
    public $TTL = '';
    /** @var string */
    public $HTTP = '';
    /** @var string */
    public $TCP = '';
    /** @var string */
    public $Status = '';

    /**
     * @return string
     */
    public function getScript()
    {
        return $this->Script;
    }

    /**
     * @return string
     */
    public function getDockerContainerID()
    {
        return $this->DockerContainerID;
    }

    /**
     * @return string
     */
    public function getShell()
    {
        return $this->Shell;
    }

    /**
     * @return string
     */
    public function getInterval()
    {
        return $this->Interval;
    }

    /**
     * @return string
     */
    public function getTimeout()
    {
        return $this->Timeout;
    }

    /**
     * @return string
     */
    public function getTTL()
    {
        return $this->TTL;
    }

    /**
     * @return string
     */
    public function getHTTP()
    {
        return $this->HTTP;
    }

    /**
     * @return string
     */
    public function getTCP()
    {
        return $this->TCP;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->Status;
    }
}