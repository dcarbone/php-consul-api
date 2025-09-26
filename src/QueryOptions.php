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

class QueryOptions implements RequestOptions
{
    public string $Namespace;
    public string $Datacenter;
    public bool $AllowStale;
    public bool $RequireConsistent;
    public bool $UseCache;
    public Time\Duration $MaxAge;
    public Time\Duration $StaleIfError;
    public int $WaitIndex;
    public string $WaitHash;
    public Time\Duration $WaitTime;
    public string $Token;
    public string $Near;
    public string $Filter;
    /** @var array<string,string> */
    public array $NodeMeta;
    public int $RelayFactor;
    public bool $LocalOnly;
    public bool $Connect;

    public Time\Duration $Timeout;

    public bool $Pretty;

    /**
     * @param null|\stdClass|array<string,string> $NodeMeta
     */
    public function __construct(
        string $Namespace = '',
        string $Datacenter = '',
        bool $AllowStale = false,
        bool $RequireConsistent = false,
        bool $UseCache = false,
        null|int|float|string|\DateInterval|Time\Duration $MaxAge = null,
        null|int|float|string|\DateInterval|Time\Duration $StaleIfError = null,
        int $WaitIndex = 0,
        string $WaitHash = '',
        null|int|float|string|\DateInterval|Time\Duration $WaitTime = null,
        string $Token = '',
        string $Near = '',
        string $Filter = '',
        null|\stdClass|array $NodeMeta = null,
        int $RelayFactor = 0,
        bool $LocalOnly = false,
        bool $Connect = false,
        null|int|float|string|\DateInterval|Time\Duration $Timeout = null,
        bool $Pretty = false,
    ) {
        $this->Namespace = $Namespace;
        $this->Datacenter = $Datacenter;
        $this->AllowStale = $AllowStale;
        $this->RequireConsistent = $RequireConsistent;
        $this->UseCache = $UseCache;
        $this->MaxAge = Time::Duration($MaxAge);
        $this->StaleIfError = Time::Duration($StaleIfError);
        $this->WaitIndex = $WaitIndex;
        $this->WaitHash = $WaitHash;
        $this->WaitTime = Time::Duration($WaitTime);
        $this->Token = $Token;
        $this->Near = $Near;
        $this->Filter = $Filter;
        $this->setNodeMeta($NodeMeta);
        $this->RelayFactor = $RelayFactor;
        $this->LocalOnly = $LocalOnly;
        $this->Connect = $Connect;
        $this->Timeout = Time::Duration($Timeout);
        $this->Pretty = $Pretty;
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

    public function isAllowStale(): bool
    {
        return $this->AllowStale;
    }

    public function setAllowStale(bool $allowStale): void
    {
        $this->AllowStale = $allowStale;
    }

    public function isRequireConsistent(): bool
    {
        return $this->RequireConsistent;
    }

    public function setRequireConsistent(bool $requireConsistent): void
    {
        $this->RequireConsistent = $requireConsistent;
    }

    public function isUseCache(): bool
    {
        return $this->UseCache;
    }

    public function setUseCache(bool $useCache): void
    {
        $this->UseCache = $useCache;
    }

    public function getMaxAge(): Time\Duration
    {
        return $this->MaxAge;
    }

    public function setMaxAge(null|int|float|string|\DateInterval|Time\Duration $maxAge): void
    {
        $this->MaxAge = Time::Duration($maxAge);
    }

    public function getStaleIfError(): Time\Duration
    {
        return $this->StaleIfError;
    }

    public function setStaleIfError(null|int|float|string|\DateInterval|Time\Duration $staleIfError): void
    {
        $this->StaleIfError = Time::Duration($staleIfError);
    }

    public function getWaitIndex(): int
    {
        return $this->WaitIndex;
    }

    public function setWaitIndex(int $waitIndex): void
    {
        $this->WaitIndex = $waitIndex;
    }

    public function getWaitTime(): Time\Duration
    {
        return $this->WaitTime;
    }

    public function setWaitTime(null|int|float|string|\DateInterval|Time\Duration $waitTime): void
    {
        $this->WaitTime = Time::Duration($waitTime);
    }

    public function getWaitHash(): string
    {
        return $this->WaitHash;
    }

    public function setWaitHash(string $waitHash): void
    {
        $this->WaitHash = $waitHash;
    }

    public function getToken(): string
    {
        return $this->Token;
    }

    public function setToken(string $token): void
    {
        $this->Token = $token;
    }

    public function getNear(): string
    {
        return $this->Near;
    }

    public function setNear(string $near): void
    {
        $this->Near = $near;
    }

    public function getFilter(): string
    {
        return $this->Filter;
    }

    public function setFilter(string $filter): void
    {
        $this->Filter = $filter;
    }

    /**
     * @return array<string,string>
     */
    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    /**
     * @param null|\stdClass|array<string,string> $nodeMeta
     * @return void
     */
    public function setNodeMeta(null|\stdClass|array $nodeMeta): void
    {
        $this->NodeMeta = [];
        if (null !== $nodeMeta) {
            foreach ($nodeMeta as $k => $v) {
                $this->NodeMeta[$k] = $v;
            }
        }
    }

    public function getRelayFactor(): int
    {
        return $this->RelayFactor;
    }

    public function setRelayFactor(int $relayFactor): void
    {
        $this->RelayFactor = $relayFactor;
    }

    public function isLocalOnly(): bool
    {
        return $this->LocalOnly;
    }

    public function setLocalOnly(bool $localOnly): void
    {
        $this->LocalOnly = $localOnly;
    }

    public function isConnect(): bool
    {
        return $this->Connect;
    }

    public function setConnect(bool $connect): void
    {
        $this->Connect = $connect;
    }

    public function getTimeout(): Time\Duration
    {
        return $this->Timeout;
    }

    public function setTimeout(null|int|float|string|\DateInterval|Time\Duration $timeout): void
    {
        $this->Timeout = Time::Duration($timeout);
    }

    public function isPretty(): bool
    {
        return $this->Pretty;
    }

    public function setPretty(bool $pretty): void
    {
        $this->Pretty = $pretty;
    }

    public function apply(Request $r): void
    {
        if ('' !== $this->Namespace) {
            $r->params->set('ns', $this->Namespace);
        }
        if ('' !== $this->Datacenter) {
            $r->params->set('dc', $this->Datacenter);
        }
        if ($this->AllowStale) {
            $r->params->set('stale', '');
        }
        if ($this->RequireConsistent) {
            $r->params->set('consistent', '');
        }
        if (0 !== $this->WaitIndex) {
            $r->params->set('index', (string)$this->WaitIndex);
        }
        if (0 < $this->WaitTime->Microseconds()) {
            $r->params->set('wait', dur_to_millisecond($this->WaitTime));
        }
        if ('' !== $this->WaitHash) {
            $r->params->set('hash', $this->WaitHash);
        }
        if ('' !== $this->Token) {
            $r->header->set('X-Consul-Token', $this->Token);
        }
        if ('' !== $this->Near) {
            $r->params->set('near', $this->Near);
        }
        if ('' !== $this->Filter) {
            $r->params->set('filter', $this->Filter);
        }
        if ([] !== $this->NodeMeta) {
            foreach ($this->NodeMeta as $k => $v) {
                $r->params->add('node-meta', "{$k}:{$v}");
            }
        }
        if (0 !== $this->RelayFactor) {
            $r->params->set('relay-factor', (string)$this->RelayFactor);
        }
        if ($this->LocalOnly) {
            $r->params->set('local-only', 'true');
        }
        if ($this->Connect) {
            $r->params->set('connect', 'true');
        }
        if ($this->UseCache && !$this->RequireConsistent) {
            $r->params->set('cached', '');
            $cc = [];
            $s  = $this->MaxAge->Seconds();
            if (0 < $s) {
                $cc[] = sprintf('max-age=%.0f', $s);
            }
            $s = $this->StaleIfError->Seconds();
            if (0 < $s) {
                $cc[] = sprintf('stale-if-error=%.0f', $s);
            }
            if ([] !== $cc) {
                $r->header->set('Cache-Control', implode(', ', $cc));
            }
        }

        if (0 < $this->Timeout->Nanoseconds()) {
            $r->timeout = $this->Timeout;
        }

        if ($this->Pretty) {
            $r->params->set('pretty', '');
        }
    }
}
