<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class KVPair extends AbstractType
{
    public string $Key;
    public int $CreateIndex;
    public int $ModifyIndex;
    public int $LockIndex;
    public int $Flags;
    public string $Value;
    public string $Session;
    public string $Namespace;
    public string $Partition;

    public function __construct(
        string $Key = '',
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        int $LockIndex = 0,
        int $Flags = 0,
        string $Value = '',
        string $Session = '',
        string $Namespace = '',
        string $Partition = '',
    ) {
        $this->Key = $Key;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->LockIndex = $LockIndex;
        $this->Flags = $Flags;
        $this->Value = $Value;
        $this->Session = $Session;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
    }

    public function getKey(): string
    {
        return $this->Key;
    }

    public function setKey(string $Key): self
    {
        $this->Key = $Key;
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

    public function getLockIndex(): int
    {
        return $this->LockIndex;
    }

    public function setLockIndex(int $LockIndex): self
    {
        $this->LockIndex = $LockIndex;
        return $this;
    }

    public function getFlags(): int
    {
        return $this->Flags;
    }

    public function setFlags(int $Flags): self
    {
        $this->Flags = $Flags;
        return $this;
    }

    public function getValue(): string
    {
        return $this->Value;
    }

    public function setValue(string $Value): self
    {
        $this->Value = $Value;
        return $this;
    }

    public function getSession(): string
    {
        return $this->Session;
    }

    public function setSession(string $Session): self
    {
        $this->Session = $Session;
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

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public function __toString(): string
    {
        return $this->Value;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Value' === $k) {
                if (null === $v) {
                    $n->Value = '';
                    continue;
                }
                $val = base64_decode($v, true);
                if (false === $val) {
                    throw new \DomainException(sprintf('Could not base64 decode value "%s"', $v));
                }
                $n->Value = $val;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Key = $this->Key;
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        $out->LockIndex = $this->LockIndex;
        $out->Flags = $this->Flags;
        $out->Value = $this->Value;
        $out->Session = $this->Session;
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        return $out;
    }
}
