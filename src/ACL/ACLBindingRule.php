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

class ACLBindingRule extends AbstractModel
{
    public string $ID;
    public string $Description;
    public string $AuthMethod;
    public string $Selector;
    public string $BindType;
    public string $BindName;
    public int $CreateIndex;
    public int $ModifyIndex;
    public string $Namespace;

    public function __construct(
        array $data = [], // Deprecated, will be removed.
        string $ID = '',
        string $Description = '',
        string $AuthMethod = '',
        string $Selector = '',
        string $BindType = '',
        string $BindName = '',
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        string $Namespace = ''
    ) {
        if ([] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
            return;
        }
        $this->ID = $ID;
        $this->Description = $Description;
        $this->AuthMethod = $AuthMethod;
        $this->Selector = $Selector;
        $this->BindType = $BindType;
        $this->BindName = $BindName;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->Namespace = $Namespace;
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;
        return $this;
    }

    public function getAuthMethod(): string
    {
        return $this->AuthMethod;
    }

    public function setAuthMethod(string $AuthMethod): self
    {
        $this->AuthMethod = $AuthMethod;
        return $this;
    }

    public function getSelector(): string
    {
        return $this->Selector;
    }

    public function setSelector(string $Selector): self
    {
        $this->Selector = $Selector;
        return $this;
    }

    public function getBindType(): string
    {
        return $this->BindType;
    }

    public function setBindType(string $BindType): self
    {
        $this->BindType = $BindType;
        return $this;
    }

    public function getBindName(): string
    {
        return $this->BindName;
    }

    public function setBindName(string $BindName): self
    {
        $this->BindName = $BindName;
        return $this;
    }

    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new static();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->ID = $this->ID;
        $out->Description = $this->Description;
        $out->AuthMethod = $this->AuthMethod;
        $out->Selector = $this->Selector;
        $out->BindType = $this->BindType;
        $out->BindName = $this->BindName;
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        return $out;
    }
}
