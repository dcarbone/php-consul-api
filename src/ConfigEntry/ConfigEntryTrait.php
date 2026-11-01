<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\PHPLib\MetaField;

use function DCarbone\PHPConsulAPI\PHPLib\uint64;

trait ConfigEntryTrait
{
    use MetaField;

    public string $Namespace;
    public BigInteger $CreateIndex;
    public BigInteger $ModifyIndex;

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function getCreateIndex(): BigInteger
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int|string|BigInteger $CreateIndex): self
    {
        $this->CreateIndex = uint64($CreateIndex);
        return $this;
    }

    public function getModifyIndex(): BigInteger
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int|string|BigInteger $ModifyIndex): self
    {
        $this->ModifyIndex = uint64($ModifyIndex);
        return $this;
    }
}
