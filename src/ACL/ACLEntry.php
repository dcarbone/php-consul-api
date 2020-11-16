<?php namespace DCarbone\PHPConsulAPI\ACL;

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
     * @param int $createIndex
     */
    public function setCreateIndex(int $createIndex) {
        $this->CreateIndex = $createIndex;
    }

    /**
     * @return int
     */
    public function getModifyIndex(): int {
        return $this->ModifyIndex;
    }

    /**
     * @param int $modifyIndex
     */
    public function setModifyIndex(int $modifyIndex) {
        $this->ModifyIndex = $modifyIndex;
    }

    /**
     * @return string
     */
    public function getID(): string {
        return $this->ID;
    }

    /**
     * @param string $id
     */
    public function setID(string $id) {
        $this->ID = $id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->Name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) {
        $this->Name = $name;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->Type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type) {
        $this->Type = $type;
    }

    /**
     * @return string
     */
    public function getRules(): string {
        return $this->Rules;
    }

    /**
     * @param string $rules
     */
    public function setRules(string $rules) {
        $this->Rules = $rules;
    }
}