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

class QueryOptions extends AbstractModel implements RequestOptions
{
    public const FIELDS = [
        self::FIELD_MAX_AGE => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_NULLABLE_DURATION,
        ],
        self::FIELD_STALE_IF_ERROR => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_NULLABLE_DURATION,
        ],
        self::FIELD_WAIT_TIME => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_NULLABLE_DURATION,
        ],
        self::FIELD_TIMEOUT => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_NULLABLE_DURATION,
        ],
    ];

    private const FIELD_MAX_AGE        = 'MaxAge';
    private const FIELD_STALE_IF_ERROR = 'StaleIfError';
    private const FIELD_WAIT_TIME      = 'WaitTime';
    private const FIELD_TIMEOUT        = 'Timeout';

    public string $Namespace = '';
    public string $Datacenter = '';
    public bool $AllowStale = false;
    public bool $RequireConsistent = false;
    public bool $UseCache = false;
    public ?Time\Duration $MaxAge = null;
    public ?Time\Duration $StaleIfError = null;
    public int $WaitIndex = 0;
    public string $WaitHash = '';
    public ?Time\Duration $WaitTime = null;
    public string $Token = '';
    public string $Near = '';
    public string $Filter = '';
    public array $NodeMeta = [];
    public int $RelayFactor = 0;
    public bool $LocalOnly = false;
    public bool $Connect = false;

    public ?Time\Duration $Timeout = null;

    public bool $Pretty = false;

    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!($this->MaxAge instanceof Time\Duration)) {
            $this->MaxAge = Time::Duration($this->MaxAge);
        }
        if (!($this->StaleIfError instanceof Time\Duration)) {
            $this->StaleIfError = Time::Duration($this->StaleIfError);
        }
        if (!($this->WaitTime instanceof Time\Duration)) {
            $this->WaitTime = Time::Duration($this->WaitTime);
        }
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

    public function setMaxAge(float|int|string|Time\Duration|null $maxAge): void
    {
        $this->MaxAge = Time::Duration($maxAge);
    }

    public function getStaleIfError(): Time\Duration
    {
        return $this->StaleIfError;
    }

    public function setStaleIfError(float|int|string|Time\Duration|null $staleIfError): void
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

    public function setWaitTime(mixed $waitTime): void
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

    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    public function setNodeMeta(array $nodeMeta): void
    {
        $this->NodeMeta = $nodeMeta;
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

    public function getTimeout(): ?Time\Duration
    {
        return $this->Timeout;
    }

    public function setTimeout(float|int|string|Time\Duration|null $timeout): void
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
            $r->params->set('index', (string) $this->WaitIndex);
        }
        if (isset($this->WaitTime) && 0 < $this->WaitTime->Microseconds()) {
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
        if (isset($this->NodeMeta) && [] !== $this->NodeMeta) {
            foreach ($this->NodeMeta as $k => $v) {
                $r->params->add('node-meta', "{$k}:{$v}");
            }
        }
        if (0 !== $this->RelayFactor) {
            $r->params->set('relay-factor', (string) $this->RelayFactor);
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

        if (null !== $this->Timeout) {
            $r->timeout = $this->Timeout;
        }

        if ($this->Pretty) {
            $r->params->set('pretty', '');
        }
    }
}
