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
 * Class WriteOptions
 */
class WriteOptions extends AbstractModel implements RequestOptions
{
    /** @var string */
    public string $Namespace = '';
    /** @var string */
    public string $Datacenter = '';
    /** @var string */
    public string $Token = '';
    /** @var int */
    public int $RelayFactor = 0;

    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $Timeout = null;

    /**
     * WriteOptions constructor.
     * @param array $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!($this->Timeout instanceof Time\Duration)) {
            $this->Timeout = Time::Duration($this->Timeout);
        }
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->Namespace = $namespace;
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
     * @return \DCarbone\Go\Time\Duration|null
     */
    public function getTimeout(): ?Time\Duration
    {
        return $this->Timeout;
    }

    /**
     * @param \DCarbone\Go\Time\Duration|float|int|string|null $timeout
     */
    public function setTimeout($timeout): void
    {
        $this->Timeout = Time::Duration($timeout);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Request $r
     */
    public function apply(Request $r): void
    {
        if ('' !== $this->Namespace) {
            $r->params->set('ns', $this->Namespace);
        }
        if ('' !== $this->Datacenter) {
            $r->params->set('dc', $this->Datacenter);
        }
        if ('' !== $this->Token) {
            $r->header->set('X-Consul-Token', $this->Token);
        }
        if (0 !== $this->RelayFactor) {
            $r->params->set('relay-factor', (string) $this->RelayFactor);
        }

        if (null !== $this->Timeout) {
            $r->timeout = $this->Timeout;
        }
    }
}
