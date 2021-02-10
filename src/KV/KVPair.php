<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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
 * Class KVPair
 */
class KVPair extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_NAMESPACE => Hydration::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_NAMESPACE = 'Namespace';

    /** @var string */
    public string $Key = '';
    /** @var int */
    public int $CreateIndex = 0;
    /** @var int */
    public int $ModifyIndex = 0;
    /** @var int */
    public int $LockIndex = 0;
    /** @var int */
    public int $Flags = 0;
    /** @var string */
    public string $Value = '';
    /** @var string */
    public string $Session = '';
    /** @var string */
    public string $Namespace = '';

    /**
     * KVPair constructor.
     * @param array $data
     * @param bool $_decodeValue
     */
    public function __construct(array $data = [], bool $_decodeValue = false)
    {
        parent::__construct($data);
        if ($_decodeValue) {
            $dec = \base64_decode($this->Value, true);
            if (false === $dec) {
                throw new \InvalidArgumentException(\sprintf('Could not base64 decode value "%s"', $this->Value));
            }
            $this->Value = $dec;
        }
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->Key;
    }

    /**
     * @param string $key
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setKey(string $key): self
    {
        $this->Key = $key;
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
     * @param int $createIndex
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
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
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setModifyIndex(int $modifyIndex): self
    {
        $this->ModifyIndex = $modifyIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getLockIndex(): int
    {
        return $this->LockIndex;
    }

    /**
     * @param int $lockIndex
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setLockIndex(int $lockIndex): self
    {
        $this->LockIndex = $lockIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getFlags(): int
    {
        return $this->Flags;
    }

    /**
     * @param int $flags
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setFlags(int $flags): self
    {
        $this->Flags = $flags;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->Value;
    }

    /**
     * @param string $value
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setValue(string $value): self
    {
        $this->Value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSession(): string
    {
        return $this->Session;
    }

    /**
     * @param string $session
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setSession(string $session): self
    {
        $this->Session = $session;
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
     * @param string $namespace
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setNamespace(string $namespace): self
    {
        $this->Namespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->Value;
    }
}
