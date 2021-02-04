<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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
 * Class KVPair
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVPair extends AbstractModel
{
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

    /** @var bool */
    private bool $_valueDecoded = false;

    /**
     * KVPair constructor.
     * @param array $data
     * @param bool $_decodeValue
     */
    public function __construct(array $data = [], bool $_decodeValue = false)
    {
        parent::__construct($data);
        if ($_decodeValue && !$this->_valueDecoded) {
            $dec = base64_decode($this->Value);
            if (false === $dec) {
                throw new \InvalidArgumentException(sprintf('Could not base64 decode value "%s"', $this->Value));
            }
            $this->Value = $dec;
            $this->_valueDecoded = true;
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
     * @param string $Key
     * @return KVPair
     */
    public function setKey(string $Key): KVPair
    {
        $this->Key = $Key;
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
     * @return KVPair
     */
    public function setCreateIndex(int $CreateIndex): KVPair
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
     * @return KVPair
     */
    public function setModifyIndex(int $ModifyIndex): KVPair
    {
        $this->ModifyIndex = $ModifyIndex;
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
     * @param int $LockIndex
     * @return KVPair
     */
    public function setLockIndex(int $LockIndex): KVPair
    {
        $this->LockIndex = $LockIndex;
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
     * @param int $Flags
     * @return KVPair
     */
    public function setFlags(int $Flags): KVPair
    {
        $this->Flags = $Flags;
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
     * @param string $Value
     * @return KVPair
     */
    public function setValue(string $Value): KVPair
    {
        $this->Value = $Value;
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
     * @param string $Session
     * @return KVPair
     */
    public function setSession(string $Session): KVPair
    {
        $this->Session = $Session;
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
     * @return KVPair
     */
    public function setNamespace(string $Namespace): KVPair
    {
        $this->Namespace = $Namespace;
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