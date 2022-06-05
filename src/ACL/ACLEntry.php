<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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
 * Class ACLEntry
 */
class ACLEntry extends AbstractModel
{
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;
    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Type = '';
    /** @var string */
    public string $Rules = '';

    /**
     * @return int
     */
    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    /**
     * @param int $createIndex
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntry
     */
    public function setCreateIndex(int $createIndex): self
    {
        $this->CreateIndex = $createIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    /**
     * @param int $modifyIndex
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntry
     */
    public function setModifyIndex(int $modifyIndex): self
    {
        $this->ModifyIndex = $modifyIndex;
        return $this;
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $id
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntry
     */
    public function setID(string $id): self
    {
        $this->ID = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $name
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntry
     */
    public function setName(string $name): self
    {
        $this->Name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->Type;
    }

    /**
     * @param string $type
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntry
     */
    public function setType(string $type): self
    {
        $this->Type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getRules(): string
    {
        return $this->Rules;
    }

    /**
     * @param string $rules
     * @return \DCarbone\PHPConsulAPI\ACL\ACLEntry
     */
    public function setRules(string $rules): self
    {
        $this->Rules = $rules;
        return $this;
    }
}
