<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

use DCarbone\Go\Time;

class QueryMeta
{
    public string $RequestUrl;
    public int $LastIndex;
    public string $LastContentHash;
    public int $LastContact;
    public bool $KnownLeader;
    public Time\Duration $RequestTime;
    public bool $AddressTranslationEnabled;
    public bool $CacheHit;
    public Time\Duration $CacheAge;

    public function __construct(
        string $RequestUrl,
        null|int|float|string|\DateInterval|Time\Duration $RequestTime,
        int $LastIndex = 0,
        string $LastContentHash = '',
        int $LastContact = 0,
        bool $KnownLeader = false,
        bool $AddressTranslationEnabled = false,
        bool $CacheHit = false,
        null|int|float|string|\DateInterval|Time\Duration $CacheAge = null
    ) {
        $this->RequestUrl = $RequestUrl;
        $this->RequestTime = Time::Duration($RequestTime);

        $this->LastIndex = $LastIndex;
        $this->LastContentHash = $LastContentHash;
        $this->LastContact = $LastContact;
        $this->KnownLeader = $KnownLeader;
        $this->AddressTranslationEnabled = $AddressTranslationEnabled;
        $this->CacheHit = $CacheHit;
        $this->CacheAge = Time::Duration($CacheAge);
    }

    public function getRequestUrl(): string
    {
        return $this->RequestUrl;
    }

    public function getLastIndex(): int
    {
        return $this->LastIndex;
    }

    public function getLastContentHash(): string
    {
        return $this->LastContentHash;
    }

    public function getLastContact(): int
    {
        return $this->LastContact;
    }

    public function isKnownLeader(): bool
    {
        return $this->KnownLeader;
    }

    public function getRequestTime(): Time\Duration
    {
        return $this->RequestTime;
    }

    public function isAddressTranslationEnabled(): bool
    {
        return $this->AddressTranslationEnabled;
    }

    public function isCacheHit(): bool
    {
        return $this->CacheHit;
    }

    public function getCacheAge(): Time\Duration
    {
        return $this->CacheAge;
    }
}
