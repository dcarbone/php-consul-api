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

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class AgentServiceRegistration
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentServiceRegistration extends AbstractModel
{
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string[] */
    public $Tags = array();
    /** @var int */
    public $Port = 0;
    /** @var string */
    public $Address = '';
    /** @var bool */
    public $EnableTagOverride = false;
    /** @var AgentCheck */
    public $Check = null;
    /** @var AgentCheck[] */
    public $Checks = array();

    /**
     * @return string
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return AgentServiceRegistration
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
     * @return AgentServiceRegistration
     */
    public function setName($Name)
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTags()
    {
        return $this->Tags;
    }

    /**
     * @param string[] $Tags
     * @return AgentServiceRegistration
     */
    public function setTags($Tags)
    {
        $this->Tags = $Tags;
        return $this;
    }

    /**
     * @param string $Tag
     * @return $this
     */
    public function addTag($Tag)
    {
        $this->Tags[] = $Tag;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->Port;
    }

    /**
     * @param int $Port
     * @return AgentServiceRegistration
     */
    public function setPort($Port)
    {
        $this->Port = $Port;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return AgentServiceRegistration
     */
    public function setAddress($Address)
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableTagOverride()
    {
        return $this->EnableTagOverride;
    }

    /**
     * @param boolean $EnableTagOverride
     * @return AgentServiceRegistration
     */
    public function setEnableTagOverride($EnableTagOverride)
    {
        $this->EnableTagOverride = $EnableTagOverride;
        return $this;
    }

    /**
     * @return AgentCheck
     */
    public function getCheck()
    {
        return $this->Check;
    }

    /**
     * @param AgentCheck $Check
     * @return AgentServiceRegistration
     */
    public function setCheck(AgentCheck $Check)
    {
        $this->Check = $Check;
        return $this;
    }

    /**
     * @return AgentCheck[]
     */
    public function getChecks()
    {
        return $this->Checks;
    }

    /**
     * @param AgentCheck[] $Checks
     * @return AgentServiceRegistration
     */
    public function setChecks(array $Checks)
    {
        foreach($Checks as $Check)
        {
            $this->addCheck($Check);
        }
        return $this;
    }

    public function addCheck(AgentCheck $Check)
    {
        $this->Checks[] = $Check;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->Name;
    }
}