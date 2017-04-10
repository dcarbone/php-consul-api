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
 * Class WriteOptions
 * @package DCarbone\PHPConsulAPI
 */
class WriteOptions extends AbstractModel {
    /** @var string */
    public $Datacenter = '';
    /** @var string */
    public $Token = '';
    /** @var int */
    public $RelayFactor = 0;

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
}