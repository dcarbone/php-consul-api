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
use DCarbone\PHPConsulAPI\HasSettableStringTags;
use DCarbone\PHPConsulAPI\HasStringTags;

/**
 * Class AgentServiceRegistration
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentServiceRegistration extends AbstractModel
{
    use HasStringTags, HasSettableStringTags;

    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var int */
    public $Port = 0;
    /** @var string */
    public $Address = '';
    /** @var bool */
    public $EnableTagOverride = false;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentCheck */
    public $Check = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentCheck[] */
    public $Checks = array();

    /**
     * AgentServiceRegistration constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->Check = new AgentCheck((array)$this->Check);
        for ($i = 0, $cnt = count($this->Checks); $i < $cnt; $i++)
        {
            $this->Checks[$i] = new AgentCheck($this->Checks[$i]);
        }
    }

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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function getCheck()
    {
        return $this->Check;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheck $Check
     * @return AgentServiceRegistration
     */
    public function setCheck(AgentCheck $Check)
    {
        $this->Check = $Check;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck[]
     */
    public function getChecks()
    {
        return $this->Checks;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheck[] $Checks
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

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentCheck $Check
     * @return $this
     */
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