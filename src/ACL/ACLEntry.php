<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class ACLEntry extends AbstractType
{
    public int $CreateIndex;
    public int $ModifyIndex;
    public string $ID;
    public string $Name;
    public string $Type;
    public string $Rules;

    /**
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        string $ID = '',
        string $Name = '',
        string $Type = '',
        string $Rules = '',
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Type = $Type;
        $this->Rules = $Rules;
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

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getType(): string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;
        return $this;
    }

    public function getRules(): string
    {
        return $this->Rules;
    }

    public function setRules(string $Rules): self
    {
        $this->Rules = $Rules;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        $out->ID = $this->ID;
        $out->Name = $this->Name;
        $out->Type = $this->Type;
        $out->Rules = $this->Rules;
        return $out;
    }
}
