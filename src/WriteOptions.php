<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

use DCarbone\Go\Time;

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
 * Class WriteOptions
 * @package DCarbone\PHPConsulAPI
 */
class WriteOptions extends AbstractModel
{
    /** @var string */
    public $Namespace = '';
    /** @var string */
    public $Datacenter = '';
    /** @var string */
    public $Token = '';
    /** @var int */
    public $RelayFactor = 0;
    /** @var \DCarbone\Go\Time\Duration|null */
    public $Timeout = null;

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
    public function setDatacenter(string $datacenter)
    {
        $this->Datacenter = $datacenter;
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
     * @return \DCarbone\Go\Time\Duration|null
     */
    public function getTimeout(): ?Time\Duration
    {
        return $this->Timeout;
    }

    /**
     * @param \DCarbone\Go\Time\Duration|string|int|float|null $timeout
     */
    public function setTimeout($timeout): void
    {
        $this->Timeout = Time::Duration($timeout);
    }
}