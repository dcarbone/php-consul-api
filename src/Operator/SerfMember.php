<?php namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class SerfMember
 * @package DCarbone\PHPConsulAPI\Operator
 */
class SerfMember extends AbstractModel {
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $Addr = '';
    /** @var int */
    public $Port = 0;
    /** @var string */
    public $Datacenter = '';
    /** @var string */
    public $Role = '';
    /** @var string */
    public $Build = '';
    /** @var int */
    public $Protocol = 0;
    /** @var string */
    public $Status = '';
    /** @var int */
    public $RTT = 0;

    /**
     * @return string
     */
    public function getID(): string {
        return $this->ID;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->Name;
    }

    /**
     * @return string
     */
    public function getAddr(): string {
        return $this->Addr;
    }

    /**
     * @return int
     */
    public function getPort(): int {
        return $this->Port;
    }

    /**
     * @return string
     */
    public function getDatacenter(): string {
        return $this->Datacenter;
    }

    /**
     * @return string
     */
    public function getRole(): string {
        return $this->Role;
    }

    /**
     * @return string
     */
    public function getBuild(): string {
        return $this->Build;
    }

    /**
     * @return int
     */
    public function getProtocol(): int {
        return $this->Protocol;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->Status;
    }

    /**
     * @return int
     */
    public function getRTT(): int {
        return $this->RTT;
    }
}