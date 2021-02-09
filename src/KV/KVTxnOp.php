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

/**
 * Class KVTxnOp
 */
class KVTxnOp extends AbstractModel
{
    /** @var string */
    public string $Verb = '';
    /** @var string */
    public string $Key = '';
    /** @var string */
    public string $Value = '';
    /** @var int */
    public int $Flags = 0;
    /** @var int */
    public int $Index = 0;
    /** @var string */
    public string $Session = '';

    /**
     * KVTxnOp constructor.
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
    public function getVerb(): string
    {
        return $this->Verb;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->Key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->Value;
    }

    /**
     * @return int
     */
    public function getFlags(): int
    {
        return $this->Flags;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->Index;
    }

    /**
     * @return string
     */
    public function getSession(): string
    {
        return $this->Session;
    }
}
