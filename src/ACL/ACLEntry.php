<?php namespace DCarbone\PHPConsulAPI\ACL;

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
 * Class ACLEntry
 * @package DCarbone\PHPConsulAPI\ACL
 */
class ACLEntry extends AbstractModel {
    /** @var int */
    public $CreateIndex = 0;
    /** @var int */
    public $ModifyIndex = 0;
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $Type = '';
    /** @var string */
    public $Rules = '';

    /**
     * @return int
     */
    public function getCreateIndex(): int {
        return $this->CreateIndex;
    }

    /**
     * @param int $CreateIndex
     */
    public function setCreateIndex(int $CreateIndex): void {
        $this->CreateIndex = $CreateIndex;
    }

    /**
     * @return int
     */
    public function getModifyIndex(): int {
        return $this->ModifyIndex;
    }

    /**
     * @param int $ModifyIndex
     */
    public function setModifyIndex(int $ModifyIndex): void {
        $this->ModifyIndex = $ModifyIndex;
    }

    /**
     * @return string
     */
    public function getID(): string {
        return $this->ID;
    }

    /**
     * @param string $ID
     */
    public function setID(string $ID): void {
        $this->ID = $ID;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->Name;
    }

    /**
     * @param string $Name
     */
    public function setName(string $Name): void {
        $this->Name = $Name;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->Type;
    }

    /**
     * @param string $Type
     */
    public function setType(string $Type): void {
        $this->Type = $Type;
    }

    /**
     * @return string
     */
    public function getRules(): string {
        return $this->Rules;
    }

    /**
     * @param string $Rules
     */
    public function setRules(string $Rules): void {
        $this->Rules = $Rules;
    }
}