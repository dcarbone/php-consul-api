<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Health\HealthCheckDefinition;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AgentCheck
 */
class AgentCheck extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_HEALTH_CHECK_DEFINITION => [
            Hydration::FIELD_TYPE     => Hydration::OBJECT,
            Hydration::FIELD_CLASS    => HealthCheckDefinition::class,
            Hydration::FIELD_NULLABLE => false,
        ],
        self::FIELD_NAMESPACE               => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
    ];

    private const FIELD_HEALTH_CHECK_DEFINITION = 'HealthCheckDefinition';
    private const FIELD_NAMESPACE               = 'Namespace';

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
    /** @var string */
    public string $Type = '';
    /** @var \DCarbone\PHPConsulAPI\Health\HealthCheckDefinition */
    public HealthCheckDefinition $HealthCheckDefinition;
    /** @var string|null */
    public ?string $Namespace = null;

    /**
     * AgentCheck constructor.
     * @param array $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!isset($this->HealthCheckDefinition)) {
            $this->HealthCheckDefinition = new HealthCheckDefinition([]);
        }
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->Node;
    }

    /**
     * @param string $node
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setNode(string $node): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setCheckID(string $checkID): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setName(string $name): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setStatus(string $status): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setNotes(string $notes): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setOutput(string $output): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setServiceID(string $serviceID): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setServiceName(string $serviceName): self
    {
        $this->ServiceName = $serviceName;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthCheckDefinition
     */
    public function getHealthCheckDefinition(): HealthCheckDefinition
    {
        return $this->HealthCheckDefinition;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Health\HealthCheckDefinition $healthCheckDefinition
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setHealthCheckDefinition(HealthCheckDefinition $healthCheckDefinition): self
    {
        $this->HealthCheckDefinition = $healthCheckDefinition;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->Namespace;
    }

    /**
     * @param string|null $namespace
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public function setNamespace(?string $namespace): self
    {
        $this->Namespace = $namespace;
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
