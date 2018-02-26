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
 * Class ServerHealth
 * @package DCarbone\PHPConsulAPI\Operator
 */
class ServerHealth extends AbstractModel {
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $Address = '';
    /** @var string */
    public $SerfStatus = '';
    /** @var string */
    public $Version = '';
    /** @var bool */
    public $Leader = false;
    /** @var string */
    public $LastContact = '';
    /** @var int */
    public $LastTerm = 0;
    /** @var int */
    public $LastIndex = 0;
    /** @var bool */
    public $Healthy = false;
    /** @var bool */
    public $Voter = false;
    /** @var string */
    public $StableSince = '';

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
    public function getAddress(): string {
        return $this->Address;
    }

    /**
     * @return string
     */
    public function getSerfStatus(): string {
        return $this->SerfStatus;
    }

    /**
     * @return string
     */
    public function getVersion(): string {
        return $this->Version;
    }

    /**
     * @return bool
     */
    public function isLeader(): bool {
        return $this->Leader;
    }

    /**
     * @return string
     */
    public function getLastContact(): string {
        return $this->LastContact;
    }

    /**
     * @return int
     */
    public function getLastTerm(): int {
        return $this->LastTerm;
    }

    /**
     * @return int
     */
    public function getLastIndex(): int {
        return $this->LastIndex;
    }

    /**
     * @return bool
     */
    public function isHealthy(): bool {
        return $this->Healthy;
    }

    /**
     * @return bool
     */
    public function isVoter(): bool {
        return $this->Voter;
    }

    /**
     * @return string
     */
    public function getStableSince(): string {
        return $this->StableSince;
    }
}