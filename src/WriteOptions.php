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

class WriteOptions implements RequestOptions
{
    public string $Namespace;
    public string $Datacenter;
    public string $Token;
    public int $RelayFactor;

    public Time\Duration $Timeout;

    public function __construct(
        array $data = [], // Deprecated do not use.
        string $Namespace = '',
        string $Datacenter = '',
        string $Token = '',
        int $RelayFactor = 0,
        null|int|float|string|\DateInterval|Time\Duration $Timeout = null,
    ) {
        if ([] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
            return;
        }
        $this->Namespace = $Namespace;
        $this->Datacenter = $Datacenter;
        $this->Token = $Token;
        $this->RelayFactor = $RelayFactor;
        $this->Timeout = Time::Duration($Timeout);
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

    public function setTimeout(null|int|float|string|\DateInterval|Time\Duration $timeout): void
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

        if (0 < $this->Timeout->Nanoseconds()) {
            $r->timeout = $this->Timeout;
        }
    }

    /**
     * @param \stdClass $decoded
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $into
     * @return self
     * @deprecated  This is only here to support construction with map.  It will be removed in a future version.
     */
    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new static();
        foreach ($decoded as $k => $v) {
            if ('Timeout' === $k) {
                $n->Timeout = Time::Duration($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }
}
