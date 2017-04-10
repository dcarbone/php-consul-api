<?php namespace DCarbone\PHPConsulAPI\Operator;

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

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class RaftServer
 * @package DCarbone\PHPConsulAPI\Operator
 */
class RaftServer extends AbstractModel {
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
    public function getID() {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return RaftServer
     */
    public function setID($ID) {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getNode() {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return RaftServer
     */
    public function setNode($Node) {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return RaftServer
     */
    public function setAddress($Address) {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isLeader() {
        return $this->Leader;
    }

    /**
     * @param boolean $Leader
     * @return RaftServer
     */
    public function setLeader($Leader) {
        $this->Leader = $Leader;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isVoter() {
        return $this->Voter;
    }

    /**
     * @param boolean $Voter
     * @return RaftServer
     */
    public function setVoter($Voter) {
        $this->Voter = $Voter;
        return $this;
    }
}