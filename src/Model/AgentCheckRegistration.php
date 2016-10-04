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
 * Class AgentCheckRegistration
 * @package DCarbone\PHPConsulAPI\Model
 */
class AgentCheckRegistration extends AgentServiceCheck
{
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $Notes = '';
    /** @var string */
    public $ServiceID = '';

    /**
     * @return string
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return AgentCheckRegistration
     */
    public function setID($ID)
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return AgentCheckRegistration
     */
    public function setName($Name)
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->Notes;
    }

    /**
     * @param string $Notes
     * @return AgentCheckRegistration
     */
    public function setNotes($Notes)
    {
        $this->Notes = $Notes;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceID()
    {
        return $this->ServiceID;
    }

    /**
     * @param string $ServiceID
     * @return AgentCheckRegistration
     */
    public function setServiceID($ServiceID)
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    /**
     * @param string $Script
     * @return AgentServiceCheck
     */
    public function setScript($Script)
    {
        $this->Script = $Script;
        return $this;
    }

    /**
     * @param string $DockerContainerID
     * @return AgentServiceCheck
     */
    public function setDockerContainerID($DockerContainerID)
    {
        $this->DockerContainerID = $DockerContainerID;
        return $this;
    }

    /**
     * @param string $Shell
     * @return AgentServiceCheck
     */
    public function setShell($Shell)
    {
        $this->Shell = $Shell;
        return $this;
    }

    /**
     * @param string $Interval
     * @return AgentServiceCheck
     */
    public function setInterval($Interval)
    {
        $this->Interval = $Interval;
        return $this;
    }

    /**
     * @param string $Timeout
     * @return AgentServiceCheck
     */
    public function setTimeout($Timeout)
    {
        $this->Timeout = $Timeout;
        return $this;
    }

    /**
     * @param string $TTL
     * @return AgentServiceCheck
     */
    public function setTTL($TTL)
    {
        $this->TTL = $TTL;
        return $this;
    }

    /**
     * @param string $HTTP
     * @return AgentServiceCheck
     */
    public function setHTTP($HTTP)
    {
        $this->HTTP = $HTTP;
        return $this;
    }

    /**
     * @param string $TCP
     * @return AgentServiceCheck
     */
    public function setTCP($TCP)
    {
        $this->TCP = $TCP;
        return $this;
    }

    /**
     * @param string $Status
     * @return AgentServiceCheck
     */
    public function setStatus($Status)
    {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->Name;
    }
}