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

class WriteOptions extends AbstractModel implements RequestOptions
{
    public string $Namespace = '';
    public string $Datacenter = '';
    public string $Token = '';
    public int $RelayFactor = 0;

    public ?Time\Duration $Timeout = null;

    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!($this->Timeout instanceof Time\Duration)) {
            $this->Timeout = Time::Duration($this->Timeout);
        }
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->Namespace = $namespace;
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $datacenter): void
    {
        $this->Datacenter = $datacenter;
    }

    public function getToken(): string
    {
        return $this->Token;
    }

    public function setToken(string $token): void
    {
        $this->Token = $token;
    }

    public function getRelayFactor(): int
    {
        return $this->RelayFactor;
    }

    public function setRelayFactor(int $relayFactor): void
    {
        $this->RelayFactor = $relayFactor;
    }

    public function getTimeout(): ?Time\Duration
    {
        return $this->Timeout;
    }

    public function setTimeout(float|int|string|Time\Duration|null $timeout): void
    {
        $this->Timeout = Time::Duration($timeout);
    }

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
