<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class AgentCheck
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentCheck extends AbstractModel
{
    /** @var string */
    public string $Node = '';
    /** @var string */
    public string $CheckID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Status = '';
    /** @var string */
    public string $Notes = '';
    /** @var string */
    public string $Output = '';
    /** @var string */
    public string $ServiceID = '';
    /** @var string */
    public string $ServiceName = '';

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return AgentCheck
     */
    public function setNode(string $Node): AgentCheck
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return string
     */
    public function getCheckID(): string
    {
        return $this->CheckID;
    }

    /**
     * @param string $CheckID
     * @return AgentCheck
     */
    public function setCheckID(string $CheckID): AgentCheck
    {
        $this->CheckID = $CheckID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return AgentCheck
     */
    public function setName(string $Name): AgentCheck
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return AgentCheck
     */
    public function setStatus(string $Status): AgentCheck
    {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->Notes;
    }

    /**
     * @param string $Notes
     * @return AgentCheck
     */
    public function setNotes(string $Notes): AgentCheck
    {
        $this->Notes = $Notes;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->Output;
    }

    /**
     * @param string $Output
     * @return AgentCheck
     */
    public function setOutput(string $Output): AgentCheck
    {
        $this->Output = $Output;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    /**
     * @param string $ServiceID
     * @return AgentCheck
     */
    public function setServiceID(string $ServiceID): AgentCheck
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    /**
     * @param string $ServiceName
     * @return AgentCheck
     */
    public function setServiceName(string $ServiceName): AgentCheck
    {
        $this->ServiceName = $ServiceName;
        return $this;
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->CheckID;
    }
}