<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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
 * Class RaftServer
 * @package DCarbone\PHPConsulAPI\Operator
 */
class RaftServer extends AbstractModel
{
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Node = '';
    /** @var string */
    public $Address = '';
    /** @var bool */
    public $Leader = false;
    /** @var bool */
    public $Voter = false;

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $id
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setID(string $id): RaftServer
    {
        $this->ID = $id;
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
     * @param string $node
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setNode(string $node): RaftServer
    {
        $this->Node = $node;
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
     * @param string $address
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setAddress(string $address): RaftServer
    {
        $this->Address = $address;
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
     * @param bool $leader
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setLeader(bool $leader): RaftServer
    {
        $this->Leader = $leader;
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
     * @param bool $voter
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer
     */
    public function setVoter(bool $voter): RaftServer
    {
        $this->Voter = $voter;
        return $this;
    }
}