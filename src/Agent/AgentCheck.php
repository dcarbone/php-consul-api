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
     * @param string $node
     * @return AgentCheck
     */
    public function setNode(string $node): AgentCheck
    {
        $this->Node = $node;
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
     * @param string $checkID
     * @return AgentCheck
     */
    public function setCheckID(string $checkID): AgentCheck
    {
        $this->CheckID = $checkID;
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
     * @param string $name
     * @return AgentCheck
     */
    public function setName(string $name): AgentCheck
    {
        $this->Name = $name;
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
     * @param string $status
     * @return AgentCheck
     */
    public function setStatus(string $status): AgentCheck
    {
        $this->Status = $status;
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
     * @param string $notes
     * @return AgentCheck
     */
    public function setNotes(string $notes): AgentCheck
    {
        $this->Notes = $notes;
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
     * @param string $output
     * @return AgentCheck
     */
    public function setOutput(string $output): AgentCheck
    {
        $this->Output = $output;
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
     * @param string $serviceID
     * @return AgentCheck
     */
    public function setServiceID(string $serviceID): AgentCheck
    {
        $this->ServiceID = $serviceID;
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
     * @param string $serviceName
     * @return AgentCheck
     */
    public function setServiceName(string $serviceName): AgentCheck
    {
        $this->ServiceName = $serviceName;
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