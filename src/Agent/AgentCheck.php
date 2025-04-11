<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

class AgentCheck extends AbstractModel
{
    public string $Node;
    public string $CheckID;
    public string $Name;
    public string $Status;
    public string $Notes;
    public string $Output;
    public string $ServiceID;
    public string $ServiceName;
    public string $Type;
    public HealthCheckDefinition $Definition;
    public string $Namespace;

    public function __construct(
        null|array $data = null,
        string $Node = '',
        string $CheckID = '',
        string $Name = '',
        string $Status = '',
        string $Notes = '',
        string $Output = '',
        string $ServiceID = '',
        string $ServiceName = '',
        string $Type = '',
        null|HealthCheckDefinition $Definition = null,
        string $Namespace = ''
    ) {
        $this->Node = $Node;
        $this->CheckID = $CheckID;
        $this->Name = $Name;
        $this->Status = $Status;
        $this->Notes = $Notes;
        $this->Output = $Output;
        $this->ServiceID = $ServiceID;
        $this->ServiceName = $ServiceName;
        $this->Type = $Type;
        $this->Definition = $Definition ?? new HealthCheckDefinition();
        $this->Namespace = $Namespace;
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
        }
    }

    public function getNode(): string
    {
        return $this->Node;
    }

    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getCheckID(): string
    {
        return $this->CheckID;
    }

    public function setCheckID(string $CheckID): self
    {
        $this->CheckID = $CheckID;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;
        return $this;
    }

    public function getNotes(): string
    {
        return $this->Notes;
    }

    public function setNotes(string $Notes): self
    {
        $this->Notes = $Notes;
        return $this;
    }

    public function getOutput(): string
    {
        return $this->Output;
    }

    public function setOutput(string $Output): self
    {
        $this->Output = $Output;
        return $this;
    }

    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    public function setServiceID(string $ServiceID): self
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    public function setServiceName(string $ServiceName): self
    {
        $this->ServiceName = $ServiceName;
        return $this;
    }

    public function getType(): string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;
        return $this;
    }

    public function getDefinition(): HealthCheckDefinition
    {
        return $this->Definition;
    }

    public function setDefinition(HealthCheckDefinition $Definition): self
    {
        $this->Definition = $Definition;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new static();
        foreach ($decoded as $k => $v) {
            if ('Definition' === $k) {
                $n->Definition = HealthCheckDefinition::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->Node = $this->Node;
        $out->CheckID = $this->CheckID;
        $out->Name = $this->Name;
        $out->Status = $this->Status;
        $out->Notes = $this->Notes;
        $out->Output = $this->Output;
        $out->ServiceID = $this->ServiceID;
        $out->ServiceName = $this->ServiceName;
        $out->Type = $this->Type;
        $out->Definition = $this->Definition->jsonSerialize();
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        return $out;
    }

    public function __toString(): string
    {
        return $this->CheckID;
    }
}
