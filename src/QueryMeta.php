<?php namespace DCarbone\PHPConsulAPI;

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

/**
 * Class QueryMeta
 * @package DCarbone\PHPConsulAPI
 */
class QueryMeta {
    /** @var string */
    public $RequestUrl = '';
    /** @var int */
    public $LastIndex = 0;
    /** @var int */
    public $LastContact = 0;
    /** @var bool */
    public $KnownLeader = false;
    /** @var int */
    public $RequestTime = 0;
    /** @var bool */
    public $AddressTranslationEnabled = false;

    /**
     * @return string
     */
    public function getRequestUrl(): string {
        return $this->RequestUrl;
    }

    /**
     * @return int
     */
    public function getLastIndex(): int {
        return $this->LastIndex;
    }

    /**
     * @return int
     */
    public function getLastContact(): int {
        return $this->LastContact;
    }

    /**
     * @return bool
     */
    public function isKnownLeader(): bool {
        return $this->KnownLeader;
    }

    /**
     * @return int
     */
    public function getRequestTime(): int {
        return $this->RequestTime;
    }

    /**
     * @return bool
     */
    public function isAddressTranslationEnabled(): bool {
        return $this->AddressTranslationEnabled;
    }
}