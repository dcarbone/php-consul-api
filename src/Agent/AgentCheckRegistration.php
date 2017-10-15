<?php namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentCheckRegistration extends AgentServiceCheck {
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $ServiceID = '';

    /**
     * @return string
     */
    public function getID(): string {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration
     */
    public function setID(string $ID): AgentCheckRegistration {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration
     */
    public function setName(string $Name): AgentCheckRegistration {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceID(): string {
        return $this->ServiceID;
    }

    /**
     * @param string $ServiceID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration
     */
    public function setServiceID(string $ServiceID): AgentCheckRegistration {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() {
        return (string)$this->Name;
    }
}