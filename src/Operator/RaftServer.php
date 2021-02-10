<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

/**
 * Class RaftServer
 */
class RaftServer extends AbstractModel
{
    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Node = '';
    /** @var string */
    public string $Address = '';
    /** @var bool */
    public bool $Leader = false;
    /** @var string */
    public string $ProtocolVersion = '';
    /** @var bool */
    public bool $Voter = false;

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLeader(): bool
    {
        return $this->Leader;
    }

    /**
     * @param bool $Leader
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setLeader(bool $Leader): self
    {
        $this->Leader = $Leader;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->ProtocolVersion;
    }

    /**
     * @param string $ProtocolVersion
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setProtocolVersion(string $ProtocolVersion): self
    {
        $this->ProtocolVersion = $ProtocolVersion;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVoter(): bool
    {
        return $this->Voter;
    }

    /**
     * @param bool $Voter
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setVoter(bool $Voter): self
    {
        $this->Voter = $Voter;
        return $this;
    }
}
