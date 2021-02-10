<?php declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class ACLBindingRule
 */
class ACLBindingRule extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_NAMESPACE => Hydration::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_NAMESPACE = 'Namespace';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Description = '';
    /** @var string */
    public string $AuthMethod = '';
    /** @var string */
    public string $Selector = '';
    /** @var string */
    public string $BindType = '';
    /** @var string */
    public string $BindName = '';
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;
    /** @var string */
    public string $Namespace = '';

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->Description;
    }

    /**
     * @param string $Description
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule
     */
    public function setDescription(string $Description): self
    {
        $this->Description = $Description;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthMethod(): string
    {
        return $this->AuthMethod;
    }

    /**
     * @param string $AuthMethod
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule
     */
    public function setAuthMethod(string $AuthMethod): self
    {
        $this->AuthMethod = $AuthMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getSelector(): string
    {
        return $this->Selector;
    }

    /**
     * @param string $Selector
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule
     */
    public function setSelector(string $Selector): self
    {
        $this->Selector = $Selector;
        return $this;
    }

    /**
     * @return string
     */
    public function getBindType(): string
    {
        return $this->BindType;
    }

    /**
     * @param string $BindType
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule
     */
    public function setBindType(string $BindType): self
    {
        $this->BindType = $BindType;
        return $this;
    }

    /**
     * @return string
     */
    public function getBindName(): string
    {
        return $this->BindName;
    }

    /**
     * @param string $BindName
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule
     */
    public function setBindName(string $BindName): self
    {
        $this->BindName = $BindName;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    /**
     * @param int $CreateIndex
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule
     */
    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
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
     * @param int $ModifyIndex
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule
     */
    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $Namespace
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }
}
