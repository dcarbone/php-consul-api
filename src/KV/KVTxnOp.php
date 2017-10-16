<?php namespace DCarbone\PHPConsulAPI\KV;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVTxnOp extends AbstractModel {
    /** @var string */
    public $Verb = '';
    /** @var string */
    public $Key = '';
    /** @var null|string */
    public $Value = null;
    /** @var int */
    public $Flags = 0;
    /** @var int */
    public $Index = 0;
    /** @var string */
    public $Session = '';

    /**
     * KVTxnOp constructor.
     * @param array $data
     * @param bool $_decodeValue
     */
    public function __construct(array $data = [], $_decodeValue = false) {
        parent::__construct($data);
        if ((bool)$_decodeValue && isset($this->Value)) {
            $this->Value = base64_decode($this->Value);
        }
    }

    /**
     * @return string
     */
    public function getVerb(): string {
        return $this->Verb;
    }

    /**
     * @return string
     */
    public function getKey(): string {
        return $this->Key;
    }

    /**
     * @return null|string
     */
    public function getValue() {
        return $this->Value;
    }

    /**
     * @return int
     */
    public function getFlags(): int {
        return $this->Flags;
    }

    /**
     * @return int
     */
    public function getIndex(): int {
        return $this->Index;
    }

    /**
     * @return string
     */
    public function getSession(): string {
        return $this->Session;
    }
}