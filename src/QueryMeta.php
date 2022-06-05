<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

use DCarbone\Go\Time;

/**
 * Class QueryMeta
 */
class QueryMeta
{
    /** @var string */
    public string $RequestUrl = '';
    /** @var int */
    public int $LastIndex = 0;
    /** @var string */
    public string $LastContentHash = '';
    /** @var int */
    public int $LastContact = 0;
    /** @var bool */
    public bool $KnownLeader = false;
    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $RequestTime = null;
    /** @var bool */
    public bool $AddressTranslationEnabled = false;
    /** @var bool */
    public bool $CacheHit = false;
    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $CacheAge = null;

    /**
     * @return string
     */
    public function getRequestUrl(): string
    {
        return $this->RequestUrl;
    }

    /**
     * @return int
     */
    public function getLastIndex(): int
    {
        return $this->LastIndex;
    }

    /**
     * @return string
     */
    public function getLastContentHash(): string
    {
        return $this->LastContentHash;
    }

    /**
     * @return int
     */
    public function getLastContact(): int
    {
        return $this->LastContact;
    }

    /**
     * @return bool
     */
    public function isKnownLeader(): bool
    {
        return $this->KnownLeader;
    }

    /**
     * @return \DCarbone\Go\Time\Duration|null
     */
    public function getRequestTime(): ?Time\Duration
    {
        return $this->RequestTime;
    }

    /**
     * @return bool
     */
    public function isAddressTranslationEnabled(): bool
    {
        return $this->AddressTranslationEnabled;
    }

    /**
     * @return bool
     */
    public function isCacheHit(): bool
    {
        return $this->CacheHit;
    }

    /**
     * @return \DCarbone\Go\Time\Duration|null
     */
    public function getCacheAge(): ?Time\Duration
    {
        return $this->CacheAge;
    }
}
