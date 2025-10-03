<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Health;

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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class HealthCheck extends AbstractType
{
    public string $Node;
    public string $CheckID;
    public string $Name;
    public string $Status;
    public string $Notes;
    public string $Output;
    public string $ServiceID;
    public string $ServiceName;
    /** @var array<string> */
    public array $ServiceTags;
    public string $Type;
    public string $Namespace;
    public string $Partition;
    public int $ExposedPort;
    public string $PeerName;
    public HealthCheckDefinition $Definition;
    public int $CreateIndex;
    public int $ModifyIndex;

    /**
     * @param array<string> $ServiceTags
     */
    public function __construct(
        string $Node = '',
        string $CheckID = '',
        string $Name = '',
        string $Status = '',
        string $Notes = '',
        string $Output = '',
        string $ServiceID = '',
        string $ServiceName = '',
        array $ServiceTags = [],
        string $Namespace = '',
        string $Partition = '',
        int $ExposedPort = 0,
        string $PeerName = '',
        null|HealthCheckDefinition $Definition = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        $this->Node = $Node;
        $this->CheckID = $CheckID;
        $this->Name = $Name;
        $this->Status = $Status;
        $this->Notes = $Notes;
        $this->Output = $Output;
        $this->ServiceID = $ServiceID;
        $this->ServiceName = $ServiceName;
        $this->setServiceTags(...$ServiceTags);
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        $this->ExposedPort = $ExposedPort;
        $this->PeerName = $PeerName;
        $this->Definition = $Definition ?? new HealthCheckDefinition();
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
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

    /**
     * @return array<string>
     */
    public function getServiceTags(): array
    {
        return $this->ServiceTags;
    }

    public function setServiceTags(string ...$ServiceTags): self
    {
        $this->ServiceTags = $ServiceTags;
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

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
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

    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Definition' === $k) {
                $n->Definition = HealthCheckDefinition::jsonUnserialize($v);
            } elseif ('ServiceTags' === $k) {
                $n->setServiceTags(...$v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Node = $this->Node;
        $out->CheckID = $this->CheckID;
        $out->Name = $this->Name;
        $out->Status = $this->Status;
        $out->Notes = $this->Notes;
        $out->Output = $this->Output;
        $out->ServiceID = $this->ServiceID;
        $out->ServiceName = $this->ServiceName;
        $out->ServiceTags = $this->ServiceTags;
        $out->Type = $this->Type;
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        $out->ExposedPort = $this->ExposedPort;
        if ('' !== $this->PeerName) {
            $out->PeerName = $this->PeerName;
        }
        $out->Definition = $this->Definition;
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
