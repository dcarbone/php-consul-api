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
    public $Key = '';
    /** @var int */
    public $CreateIndex = 0;
    /** @var int */
    public $ModifyIndex = 0;
    /** @var int */
    public $LockIndex = 0;
    /** @var int */
    public $Flags = 0;
    /** @var null|string */
    public $Value = null;
    /** @var string */
    public $Session = '';
    /** @var string */
    public $Namespace = '';

    /**
     * KVPair constructor.
     * @param array $data
     * @param bool $_decodeValue
     */
    public function __construct(array $data = [], bool $_decodeValue = false)
    {
        parent::__construct($data);
        if ((bool)$_decodeValue && isset($this->Value)) {
            $this->Value = base64_decode($this->Value);
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
    public function setKey(string $key): KVPair
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
    public function setCreateIndex(int $createIndex): KVPair
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
    public function setModifyIndex(int $modifyIndex): KVPair
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
    public function setLockIndex(int $lockIndex): KVPair
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
    public function setFlags(int $flags): KVPair
    {
        $this->Flags = $flags;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->Value;
    }

    /**
     * @param null|string $value
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function setValue(string $value): KVPair
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
    public function setSession(string $session): KVPair
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
    public function setNamespace(string $namespace): KVPair
    {
        $this->Namespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->Value;
    }
}