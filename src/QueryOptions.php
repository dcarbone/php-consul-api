<?php namespace DCarbone\PHPConsulAPI;

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

/**
 * Class QueryOptions
 * @package DCarbone\PHPConsulAPI
 */
class QueryOptions extends AbstractModel {
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
    public function getDatacenter(): string {
        return $this->Datacenter;
    }

    /**
     * @param string $Datacenter
     */
    public function setDatacenter(string $Datacenter): void {
        $this->Datacenter = $Datacenter;
    }

    /**
     * @return bool
     */
    public function isAllowStale(): bool {
        return $this->AllowStale;
    }

    /**
     * @param bool $AllowStale
     */
    public function setAllowStale(bool $AllowStale): void {
        $this->AllowStale = $AllowStale;
    }

    /**
     * @return bool
     */
    public function isRequireConsistent(): bool {
        return $this->RequireConsistent;
    }

    /**
     * @param bool $RequireConsistent
     */
    public function setRequireConsistent(bool $RequireConsistent): void {
        $this->RequireConsistent = $RequireConsistent;
    }

    /**
     * @return int
     */
    public function getWaitIndex(): int {
        return $this->WaitIndex;
    }

    /**
     * @param int $WaitIndex
     */
    public function setWaitIndex(int $WaitIndex): void {
        $this->WaitIndex = $WaitIndex;
    }

    /**
     * @return int
     */
    public function getWaitTime(): int {
        return $this->WaitTime;
    }

    /**
     * @param int $WaitTime
     */
    public function setWaitTime(int $WaitTime): void {
        $this->WaitTime = $WaitTime;
    }

    /**
     * @return string
     */
    public function getToken(): string {
        return $this->Token;
    }

    /**
     * @param string $Token
     */
    public function setToken(string $Token): void {
        $this->Token = $Token;
    }

    /**
     * @return string
     */
    public function getNear(): string {
        return $this->Near;
    }

    /**
     * @param string $Near
     */
    public function setNear(string $Near): void {
        $this->Near = $Near;
    }

    /**
     * @return array
     */
    public function getNodeMeta(): array {
        return $this->NodeMeta;
    }

    /**
     * @param array $NodeMeta
     */
    public function setNodeMeta(array $NodeMeta): void {
        $this->NodeMeta = $NodeMeta;
    }

    /**
     * @return int
     */
    public function getRelayFactor(): int {
        return $this->RelayFactor;
    }

    /**
     * @param int $RelayFactor
     */
    public function setRelayFactor(int $RelayFactor): void {
        $this->RelayFactor = $RelayFactor;
    }

    /**
     * @return bool
     */
    public function isPretty(): bool {
        return $this->Pretty;
    }

    /**
     * @param bool $Pretty
     */
    public function setPretty(bool $Pretty): void {
        $this->Pretty = $Pretty;
    }
}