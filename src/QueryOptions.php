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
    public function getDatacenter() {
        return $this->Datacenter;
    }

    /**
     * @param string $Datacenter
     */
    public function setDatacenter($Datacenter) {
        $this->Datacenter = $Datacenter;
    }

    /**
     * @return bool
     */
    public function isAllowStale() {
        return $this->AllowStale;
    }

    /**
     * @param bool $AllowStale
     */
    public function setAllowStale($AllowStale) {
        $this->AllowStale = $AllowStale;
    }

    /**
     * @return bool
     */
    public function isRequireConsistent() {
        return $this->RequireConsistent;
    }

    /**
     * @param bool $RequireConsistent
     */
    public function setRequireConsistent($RequireConsistent) {
        $this->RequireConsistent = $RequireConsistent;
    }

    /**
     * @return int
     */
    public function getWaitIndex() {
        return $this->WaitIndex;
    }

    /**
     * @param int $WaitIndex
     */
    public function setWaitIndex($WaitIndex) {
        $this->WaitIndex = $WaitIndex;
    }

    /**
     * @return int
     */
    public function getWaitTime() {
        return $this->WaitTime;
    }

    /**
     * @param int $WaitTime
     */
    public function setWaitTime($WaitTime) {
        $this->WaitTime = $WaitTime;
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->Token;
    }

    /**
     * @param string $Token
     */
    public function setToken($Token) {
        $this->Token = $Token;
    }

    /**
     * @return string
     */
    public function getNear() {
        return $this->Near;
    }

    /**
     * @param string $Near
     */
    public function setNear($Near) {
        $this->Near = $Near;
    }

    /**
     * @return array
     */
    public function getNodeMeta() {
        return $this->NodeMeta;
    }

    /**
     * @param array $NodeMeta
     */
    public function setNodeMeta($NodeMeta) {
        $this->NodeMeta = $NodeMeta;
    }

    /**
     * @return int
     */
    public function getRelayFactor() {
        return $this->RelayFactor;
    }

    /**
     * @param int $RelayFactor
     */
    public function setRelayFactor($RelayFactor) {
        $this->RelayFactor = $RelayFactor;
    }

    /**
     * @return bool
     */
    public function isPretty() {
        return $this->Pretty;
    }

    /**
     * @param bool $Pretty
     */
    public function setPretty($Pretty) {
        $this->Pretty = $Pretty;
    }
}