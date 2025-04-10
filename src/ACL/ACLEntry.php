<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

class ACLEntry extends AbstractModel
{
    public int $CreateIndex = 0;
    public int $ModifyIndex = 0;
    public string $ID = '';
    public string $Name = '';
    public string $Type = '';
    public string $Rules = '';

    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int $createIndex): self
    {
        $this->CreateIndex = $createIndex;
        return $this;
    }

    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int $modifyIndex): self
    {
        $this->ModifyIndex = $modifyIndex;
        return $this;
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $id): self
    {
        $this->ID = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $name): self
    {
        $this->Name = $name;
        return $this;
    }

    public function getType(): string
    {
        return $this->Type;
    }

    public function setType(string $type): self
    {
        $this->Type = $type;
        return $this;
    }

    public function getRules(): string
    {
        return $this->Rules;
    }

    public function setRules(string $rules): self
    {
        $this->Rules = $rules;
        return $this;
    }
}
