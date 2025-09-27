<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

class RaftServer extends AbstractModel
{
    public string $ID;
    public string $Node;
    public string $Address;
    public bool $Leader;
    public string $ProtocolVersion;
    public bool $Voter;

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
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

    public function getAddress(): string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    public function isLeader(): bool
    {
        return $this->Leader;
    }

    public function setLeader(bool $Leader): self
    {
        $this->Leader = $Leader;
        return $this;
    }

    public function getProtocolVersion(): string
    {
        return $this->ProtocolVersion;
    }

    public function setProtocolVersion(string $ProtocolVersion): self
    {
        $this->ProtocolVersion = $ProtocolVersion;
        return $this;
    }

    public function isVoter(): bool
    {
        return $this->Voter;
    }

    public function setVoter(bool $Voter): self
    {
        $this->Voter = $Voter;
        return $this;
    }
}
