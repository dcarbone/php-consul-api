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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;
use DCarbone\PHPConsulAPI\Transcoding;

class KVPair extends AbstractType
{
    protected const FIELDS = [
        self::FIELD_NAMESPACE => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_NAMESPACE = 'Namespace';

    public string $Key = '';
    public int $CreateIndex = 0;
    public int $ModifyIndex = 0;
    public int $LockIndex = 0;
    public int $Flags = 0;
    public string $Value = '';
    public string $Session = '';
    public string $Namespace = '';

    /**
     * KVPair constructor.
     * @param bool $_decodeValue
     */
    public function __construct(array $data = [], bool $_decodeValue = false)
    {
        parent::__construct($data);
        if ($_decodeValue) {
            $dec = base64_decode($this->Value, true);
            if (false === $dec) {
                throw new \InvalidArgumentException(sprintf('Could not base64 decode value "%s"', $this->Value));
            }
            $this->Value = $dec;
        }
    }

    public function getKey(): string
    {
        return $this->Key;
    }

    public function setKey(string $key): self
    {
        $this->Key = $key;
        return $this;
    }

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

    public function getLockIndex(): int
    {
        return $this->LockIndex;
    }

    public function setLockIndex(int $lockIndex): self
    {
        $this->LockIndex = $lockIndex;
        return $this;
    }

    public function getFlags(): int
    {
        return $this->Flags;
    }

    public function setFlags(int $flags): self
    {
        $this->Flags = $flags;
        return $this;
    }

    public function getValue(): string
    {
        return $this->Value;
    }

    public function setValue(string $value): self
    {
        $this->Value = $value;
        return $this;
    }

    public function getSession(): string
    {
        return $this->Session;
    }

    public function setSession(string $session): self
    {
        $this->Session = $session;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->Namespace = $namespace;
        return $this;
    }

    public function __toString(): string
    {
        return $this->Value;
    }
}
