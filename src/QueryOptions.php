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

use DCarbone\Go\Time;

/**
 * Class QueryOptions
 * @package DCarbone\PHPConsulAPI
 */
class QueryOptions extends AbstractModel
{
    /** @var string */
    public $Namespace = '';
    /** @var string */
    public $Datacenter = '';
    /** @var bool */
    public $AllowStale = false;
    /** @var bool */
    public $RequireConsistent = false;
    /** @var bool */
    public $UsageCache = false;
    /** @var \DCarbone\Go\Time\Duration|null */
    public $MaxAge = null;
    /** @var \DCarbone\Go\Time\Duration|null */
    public $StaleIfError = null;
    /** @var int */
    public $WaitIndex = 0;
    /** @var string */
    public $WaitHash = '';
    /** @var int */
    public $WaitTime = 0;
    /** @var string */
    public $Token = '';
    /** @var string */
    public $Near = '';
    /** @var array */
    public $NodeMeta = [];
    /** @var int */
    public $RelayFactor = 0;
    /** @var bool */
    public $LocalOnly = false;
    /** @var bool */
    public $Connect = false;

    /** @var bool */
    public $Pretty = false;

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $Namespace
     */
    public function setNamespace(string $Namespace): void
    {
        $this->Namespace = $Namespace;
    }

    /**
     * @return string
     */
    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    /**
     * @param string $datacenter
     */
    public function setDatacenter(string $datacenter): void
    {
        $this->Datacenter = $datacenter;
    }

    /**
     * @return bool
     */
    public function isAllowStale(): bool
    {
        return $this->AllowStale;
    }

    /**
     * @param bool $allowStale
     */
    public function setAllowStale(bool $allowStale): void
    {
        $this->AllowStale = $allowStale;
    }

    /**
     * @return bool
     */
    public function isRequireConsistent(): bool
    {
        return $this->RequireConsistent;
    }

    /**
     * @param bool $requireConsistent
     */
    public function setRequireConsistent(bool $requireConsistent): void
    {
        $this->RequireConsistent = $requireConsistent;
    }

    /**
     * @return bool
     */
    public function isUsageCache(): bool
    {
        return $this->UsageCache;
    }

    /**
     * @param bool $UsageCache
     */
    public function setUsageCache(bool $UsageCache): void
    {
        $this->UsageCache = $UsageCache;
    }

    /**
     * @return \DCarbone\Go\Time\Duration|null
     */
    public function getMaxAge(): ?\DCarbone\Go\Time\Duration
    {
        return $this->MaxAge;
    }

    /**
     * @param \DCarbone\Go\Time\Duration|null $MaxAge
     */
    public function setMaxAge(?\DCarbone\Go\Time\Duration $MaxAge): void
    {
        $this->MaxAge = $MaxAge;
    }

    /**
     * @return \DCarbone\Go\Time\Duration|null
     */
    public function getStaleIfError(): ?\DCarbone\Go\Time\Duration
    {
        return $this->StaleIfError;
    }

    /**
     * @param \DCarbone\Go\Time\Duration|string|int|float|null $staleIfError
     */
    public function setStaleIfError($staleIfError): void
    {
        $this->StaleIfError = Time::Duration($staleIfError);
    }

    /**
     * @return int
     */
    public function getWaitIndex(): int
    {
        return $this->WaitIndex;
    }

    /**
     * @param int $waitIndex
     */
    public function setWaitIndex(int $waitIndex): void
    {
        $this->WaitIndex = $waitIndex;
    }

    /**
     * @return int
     */
    public function getWaitTime(): int
    {
        return $this->WaitTime;
    }

    /**
     * @param int $waitTime
     */
    public function setWaitTime(int $waitTime): void
    {
        $this->WaitTime = $waitTime;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->Token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->Token = $token;
    }

    /**
     * @return string
     */
    public function getNear(): string
    {
        return $this->Near;
    }

    /**
     * @param string $near
     */
    public function setNear(string $near): void
    {
        $this->Near = $near;
    }

    /**
     * @return array
     */
    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    /**
     * @param array $nodeMeta
     */
    public function setNodeMeta(array $nodeMeta): void
    {
        $this->NodeMeta = $nodeMeta;
    }

    /**
     * @return int
     */
    public function getRelayFactor(): int
    {
        return $this->RelayFactor;
    }

    /**
     * @param int $relayFactor
     */
    public function setRelayFactor(int $relayFactor): void
    {
        $this->RelayFactor = $relayFactor;
    }

    /**
     * @return bool
     */
    public function isPretty(): bool
    {
        return $this->Pretty;
    }

    /**
     * @param bool $pretty
     */
    public function setPretty(bool $pretty): void
    {
        $this->Pretty = $pretty;
    }
}