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
use function DCarbone\PHPConsulAPI\PHPLib\dur_to_millisecond;

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
     * @param array<string,string> $NodeMeta
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
        array $NodeMeta = [],
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

    public function setNamespace(string $namespace): self
    {
        $this->Namespace = $namespace;
        return $this;
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $datacenter): self
    {
        $this->Datacenter = $datacenter;
        return $this;
    }

    public function isAllowStale(): bool
    {
        return $this->AllowStale;
    }

    public function setAllowStale(bool $allowStale): self
    {
        $this->AllowStale = $allowStale;
        return $this;
    }

    public function isRequireConsistent(): bool
    {
        return $this->RequireConsistent;
    }

    public function setRequireConsistent(bool $requireConsistent): self
    {
        $this->RequireConsistent = $requireConsistent;
        return $this;
    }

    public function isUseCache(): bool
    {
        return $this->UseCache;
    }

    public function setUseCache(bool $useCache): self
    {
        $this->UseCache = $useCache;
        return $this;
    }

    public function getMaxAge(): Time\Duration
    {
        return $this->MaxAge;
    }

    public function setMaxAge(null|int|float|string|\DateInterval|Time\Duration $maxAge): self
    {
        $this->MaxAge = Time::Duration($maxAge);
        return $this;
    }

    public function getStaleIfError(): Time\Duration
    {
        return $this->StaleIfError;
    }

    public function setStaleIfError(null|int|float|string|\DateInterval|Time\Duration $staleIfError): self
    {
        $this->StaleIfError = Time::Duration($staleIfError);
        return $this;
    }

    public function getWaitIndex(): int
    {
        return $this->WaitIndex;
    }

    public function setWaitIndex(int $waitIndex): self
    {
        $this->WaitIndex = $waitIndex;
        return $this;
    }

    public function getWaitTime(): Time\Duration
    {
        return $this->WaitTime;
    }

    public function setWaitTime(null|int|float|string|\DateInterval|Time\Duration $waitTime): self
    {
        $this->WaitTime = Time::Duration($waitTime);
        return $this;
    }

    public function getWaitHash(): string
    {
        return $this->WaitHash;
    }

    public function setWaitHash(string $waitHash): self
    {
        $this->WaitHash = $waitHash;
        return $this;
    }

    public function getToken(): string
    {
        return $this->Token;
    }

    public function setToken(string $token): self
    {
        $this->Token = $token;
        return $this;
    }

    public function getNear(): string
    {
        return $this->Near;
    }

    public function setNear(string $near): self
    {
        $this->Near = $near;
        return $this;
    }

    public function getFilter(): string
    {
        return $this->Filter;
    }

    public function setFilter(string $filter): self
    {
        $this->Filter = $filter;
        return $this;
    }

    /**
     * @return array<string,string>
     */
    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    /**
     * @param array<string,string> $nodeMeta
     */
    public function setNodeMeta(array $nodeMeta): self
    {
        $this->NodeMeta = $nodeMeta;
        return $this;
    }

    public function getRelayFactor(): int
    {
        return $this->RelayFactor;
    }

    public function setRelayFactor(int $relayFactor): self
    {
        $this->RelayFactor = $relayFactor;
        return $this;
    }

    public function isLocalOnly(): bool
    {
        return $this->LocalOnly;
    }

    public function setLocalOnly(bool $localOnly): self
    {
        $this->LocalOnly = $localOnly;
        return $this;
    }

    public function isConnect(): bool
    {
        return $this->Connect;
    }

    public function setConnect(bool $connect): self
    {
        $this->Connect = $connect;
        return $this;
    }

    public function getTimeout(): Time\Duration
    {
        return $this->Timeout;
    }

    public function setTimeout(null|int|float|string|\DateInterval|Time\Duration $timeout): self
    {
        $this->Timeout = Time::Duration($timeout);
        return $this;
    }

    public function isPretty(): bool
    {
        return $this->Pretty;
    }

    public function setPretty(bool $pretty): self
    {
        $this->Pretty = $pretty;
        return $this;
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
