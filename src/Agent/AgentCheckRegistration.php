<?php namespace DCarbone\SimpleConsulPHP\Agent;

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

use DCarbone\SimpleConsulPHP\AbstractDefinedCollection;

/**
 * Class AgentCheckRegistration
 * @package DCarbone\SimpleConsulPHP\Agent
 */
class AgentCheckRegistration extends AbstractDefinedCollection
{
    /** @var array */
    protected $_storage = array(
        'ID' => null,
        'Name' => null,
        'Notes' => null,
        'ServiceID' => null,
        'AgentServiceCheck' => null,
    );

    /**
     * @return string
     */
    public function getID()
    {
        return $this['ID'];
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setID($id)
    {
        $this->_storage['ID'] = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_storage['Name'];
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->_storage['Name'] = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->_storage['Notes'];
    }

    /**
     * @param string $notes
     * @return $this
     */
    public function setNotes($notes)
    {
        $this->_storage['Notes'] = $notes;
        return $this;
    }

    /**
     * @return AgentServiceCheck
     */
    public function getAgentServiceCheck()
    {
        return $this->_storage['AgentServiceCheck'];
    }

    /**
     * @param AgentServiceCheck $agentServiceCheck
     * @return $this
     */
    public function setAgentServiceCheck(AgentServiceCheck $agentServiceCheck)
    {
        $this->_storage['AgentServiceCheck'] = $agentServiceCheck;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}