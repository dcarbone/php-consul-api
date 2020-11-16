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
 * Class QueryOptions
 * @package DCarbone\PHPConsulAPI
 */
class QueryOptions extends AbstractModel
{
    /** @var string */
    public $Datacenter = '';
    /** @var bool */
    public $AllowStale = false;
    /** @var bool */
    public $RequireConsistent = false;
    /** @var int */
    public $WaitIndex = 0;
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
    public $Pretty = false;

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
    public function setAllowStale(bool $allowStale)
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
    public function setRequireConsistent(bool $requireConsistent)
    {
        $this->RequireConsistent = $requireConsistent;
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
    public function setWaitIndex(int $waitIndex)
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
    public function setWaitTime(int $waitTime)
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
    public function setToken(string $token)
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
    public function setNear(string $near)
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
    public function setNodeMeta(array $nodeMeta)
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
    public function setRelayFactor(int $relayFactor)
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
    public function setPretty(bool $pretty)
    {
        $this->Pretty = $pretty;
    }
}