<?php declare(strict_types=1);

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
 * Class QueryOptions
 */
class QueryOptions extends AbstractModel implements RequestOptions
{
    private const FIELD_MAX_AGE        = 'MaxAge';
    private const FIELD_STALE_IF_ERROR = 'StaleIfError';
    private const FIELD_WAIT_TIME      = 'WaitTime';
    private const FIELD_TIMEOUT        = 'Timeout';

    /** @var string */
    public string $Namespace = '';
    /** @var string */
    public string $Datacenter = '';
    /** @var bool */
    public bool $AllowStale = false;
    /** @var bool */
    public bool $RequireConsistent = false;
    /** @var bool */
    public bool $UseCache = false;
    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $MaxAge = null;
    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $StaleIfError = null;
    /** @var int */
    public int $WaitIndex = 0;
    /** @var string */
    public string $WaitHash = '';
    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $WaitTime = null;
    /** @var string */
    public string $Token = '';
    /** @var string */
    public string $Near = '';
    /** @var string */
    public string $Filter = '';
    /** @var array */
    public array $NodeMeta = [];
    /** @var int */
    public int $RelayFactor = 0;
    /** @var bool */
    public bool $LocalOnly = false;
    /** @var bool */
    public bool $Connect = false;

    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $Timeout = null;

    /** @var bool */
    public bool $Pretty = false;

    /** @var array[] */
    protected const FIELDS = [
        self::FIELD_MAX_AGE => [
            Hydration::FIELD_CALLBACK => Hydration::CALLABLE_HYDRATE_NULLABLE_DURATION,
        ],
        self::FIELD_STALE_IF_ERROR => [
            Hydration::FIELD_CALLBACK => Hydration::CALLABLE_HYDRATE_NULLABLE_DURATION,
        ],
        self::FIELD_WAIT_TIME => [
            Hydration::FIELD_CALLBACK => Hydration::CALLABLE_HYDRATE_NULLABLE_DURATION,
        ],
        self::FIELD_TIMEOUT => [
            Hydration::FIELD_CALLBACK => Hydration::CALLABLE_HYDRATE_NULLABLE_DURATION,
        ],
    ];

    /**
     * QueryOptions constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
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
     * @return bool
     */
    public function isAllowStale(): bool
    {
        return $this->AllowStale;
    }

    /**
     * @param bool $allowStale
     */
    public function setAllowStale(bool $allowStale): void
    {
        $this->AllowStale = $allowStale;
    }

    /**
     * @return bool
     */
    public function isRequireConsistent(): bool
    {
        return $this->RequireConsistent;
    }

    /**
     * @param bool $requireConsistent
     */
    public function setRequireConsistent(bool $requireConsistent): void
    {
        $this->RequireConsistent = $requireConsistent;
    }

    /**
     * @return bool
     */
    public function isUseCache(): bool
    {
        return $this->UseCache;
    }

    /**
     * @param bool $useCache
     */
    public function setUseCache(bool $useCache): void
    {
        $this->UseCache = $useCache;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getMaxAge(): Time\Duration
    {
        return $this->MaxAge;
    }

    /**
     * @param \DCarbone\Go\Time\Duration|float|int|string|null $maxAge
     */
    public function setMaxAge($maxAge): void
    {
        $this->MaxAge = Time::Duration($maxAge);
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getStaleIfError(): Time\Duration
    {
        return $this->StaleIfError;
    }

    /**
     * @param \DCarbone\Go\Time\Duration|float|int|string|null $staleIfError
     */
    public function setStaleIfError($staleIfError): void
    {
        $this->StaleIfError = Time::Duration($staleIfError);
    }

    /**
     * @return int
     */
    public function getWaitIndex(): int
    {
        return $this->WaitIndex;
    }

    /**
     * @param int $waitIndex
     */
    public function setWaitIndex(int $waitIndex): void
    {
        $this->WaitIndex = $waitIndex;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getWaitTime(): Time\Duration
    {
        return $this->WaitTime;
    }

    /**
     * @param mixed $waitTime
     */
    public function setWaitTime($waitTime): void
    {
        $this->WaitTime = Time::Duration($waitTime);
    }

    /**
     * @return string
     */
    public function getWaitHash(): string
    {
        return $this->WaitHash;
    }

    /**
     * @param string $waitHash
     */
    public function setWaitHash(string $waitHash): void
    {
        $this->WaitHash = $waitHash;
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
     * @return string
     */
    public function getNear(): string
    {
        return $this->Near;
    }

    /**
     * @param string $near
     */
    public function setNear(string $near): void
    {
        $this->Near = $near;
    }

    /**
     * @return string
     */
    public function getFilter(): string
    {
        return $this->Filter;
    }

    /**
     * @param string $filter
     */
    public function setFilter(string $filter): void
    {
        $this->Filter = $filter;
    }

    /**
     * @return array
     */
    public function getNodeMeta(): array
    {
        return $this->NodeMeta;
    }

    /**
     * @param array $nodeMeta
     */
    public function setNodeMeta(array $nodeMeta): void
    {
        $this->NodeMeta = $nodeMeta;
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
     * @return bool
     */
    public function isLocalOnly(): bool
    {
        return $this->LocalOnly;
    }

    /**
     * @param bool $localOnly
     */
    public function setLocalOnly(bool $localOnly): void
    {
        $this->LocalOnly = $localOnly;
    }

    /**
     * @return bool
     */
    public function isConnect(): bool
    {
        return $this->Connect;
    }

    /**
     * @param bool $connect
     */
    public function setConnect(bool $connect): void
    {
        $this->Connect = $connect;
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
     * @return bool
     */
    public function isPretty(): bool
    {
        return $this->Pretty;
    }

    /**
     * @param bool $pretty
     */
    public function setPretty(bool $pretty): void
    {
        $this->Pretty = $pretty;
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
        if ($this->AllowStale) {
            $r->params->set('stale', '');
        }
        if ($this->RequireConsistent) {
            $r->params->set('consistent', '');
        }
        if (0 !== $this->WaitIndex) {
            $r->params->set('index', (string) $this->WaitIndex);
        }
        if (0 !== $this->WaitTime) {
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
                $cc[] = \sprintf('max-age=%.0f', $s);
            }
            $s = $this->StaleIfError->Seconds();
            if (0 < $s) {
                $cc[] = \sprintf('stale-if-error=%.0f', $s);
            }
            if ([] !== $cc) {
                $r->header->set('Cache-Control', \implode(', ', $cc));
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
