<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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
use DCarbone\PHPConsulAPI\AbstractModel;

class ServiceRouteDestination extends AbstractModel
{
    public string $Service;
    public string $ServiceSubset;
    public string $Namespace;
    public string $Partition;
    public string $PrefixRewrite;
    public Time\Duration $RequestTimeout;
    public Time\Duration $IdleTimeout;
    public int $NumRetries;
    public bool $RetryOnConnectFailure;
    /** @var array<int> */
    public array $RetryOnStatusCodes;
    /** @var array<string> */
    public array $RetryOn;
    public null|HTTPHeaderModifiers $RequestHeaders;
    public null|HTTPHeaderModifiers $ResponseHeaders;

    /**
     * @param array<int> $RetryOnStatusCodes
     * @param array<string> $RetryOn
     */
    public function __construct(
        string $Service = '',
        string $ServiceSubset = '',
        string $Namespace = '',
        string $Partition = '',
        string $PrefixRewrite = '',
        null|string|int|float|\DateInterval|Time\Duration $RequestTimeout = null,
        null|string|int|float|\DateInterval|Time\Duration $IdleTimeout = null,
        int $NumRetries = 0,
        bool $RetryOnConnectFailure = false,
        array $RetryOnStatusCodes = [],
        array $RetryOn = [],
        null|HTTPHeaderModifiers $RequestHeaders = null,
        null|HTTPHeaderModifiers $ResponseHeaders = null,
    ) {
        $this->Service = $Service;
        $this->ServiceSubset = $ServiceSubset;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        $this->PrefixRewrite = $PrefixRewrite;
        $this->RequestTimeout = Time::Duration($RequestTimeout);
        $this->IdleTimeout = Time::Duration($IdleTimeout);
        $this->NumRetries = $NumRetries;
        $this->RetryOnConnectFailure = $RetryOnConnectFailure;
        $this->setRetryOnStatusCodes(...$RetryOnStatusCodes);
        $this->setRetryOn(...$RetryOn);
        $this->RequestHeaders = $RequestHeaders;
        $this->ResponseHeaders = $ResponseHeaders;
    }

    public function getService(): string
    {
        return $this->Service;
    }

    public function setService(string $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getServiceSubset(): string
    {
        return $this->ServiceSubset;
    }

    public function setServiceSubset(string $ServiceSubset): self
    {
        $this->ServiceSubset = $ServiceSubset;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function  getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public function getPrefixRewrite(): string
    {
        return $this->PrefixRewrite;
    }

    public function setPrefixRewrite(string $PrefixRewrite): self
    {
        $this->PrefixRewrite = $PrefixRewrite;
        return $this;
    }

    public function getRequestTimeout(): Time\Duration
    {
        return $this->RequestTimeout;
    }

    public function setRequestTimeout(null|string|int|float|\DateInterval|Time\Duration $RequestTimeout): self
    {
        $this->RequestTimeout = Time::Duration($RequestTimeout);
        return $this;
    }

    public function  getIdleTimeout(): Time\Duration
    {
        return $this->IdleTimeout;
    }

    public function setIdleTimeout(null|string|int|float|\DateInterval|Time\Duration $IdleTimeout): self
    {
        $this->IdleTimeout = Time::Duration($IdleTimeout);
        return $this;
    }

    public function getNumRetries(): int
    {
        return $this->NumRetries;
    }

    public function setNumRetries(int $NumRetries): self
    {
        $this->NumRetries = $NumRetries;
        return $this;
    }

    public function isRetryOnConnectFailure(): bool
    {
        return $this->RetryOnConnectFailure;
    }

    public function setRetryOnConnectFailure(bool $RetryOnConnectFailure): self
    {
        $this->RetryOnConnectFailure = $RetryOnConnectFailure;
        return $this;
    }

    /**
     * @return array<int>
     */
    public function getRetryOnStatusCodes(): array
    {
        return $this->RetryOnStatusCodes;
    }

    public function setRetryOnStatusCodes(int ...$RetryOnStatusCodes): self
    {
        $this->RetryOnStatusCodes = $RetryOnStatusCodes;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getRetryOn(): array
    {
        return $this->RetryOn;
    }

    public function setRetryOn(string ...$RetryOn): self
    {
        $this->RetryOn = $RetryOn;
        return $this;
    }

    public function getRequestHeaders(): null|HTTPHeaderModifiers
    {
        return $this->RequestHeaders;
    }

    public function setRequestHeaders(null|HTTPHeaderModifiers $RequestHeaders): self
    {
        $this->RequestHeaders = $RequestHeaders;
        return $this;
    }

    public function getResponseHeaders(): null|HTTPHeaderModifiers
    {
        return $this->ResponseHeaders;
    }

    public function setResponseHeaders(null|HTTPHeaderModifiers $ResponseHeaders): self
    {
        $this->ResponseHeaders = $ResponseHeaders;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('service_subset' === $k) {
                $n->ServiceSubset = $v;
            } elseif ('prefix_rewrite' === $k) {
                $n->PrefixRewrite = $v;
            } elseif ('RequestTimeout' === $k || 'request_timeout' === $k) {
                $n->RequestTimeout = Time::Duration($v);
            } elseif ('IdleTimeout' === $k || 'idle_timeout' === $k) {
                $n->IdleTimeout = Time::Duration($v);
            } elseif ('num_retries' === $k) {
                $n->NumRetries = $v;
            } elseif ('retry_on_connect_failure' === $k) {
                $n->RetryOnConnectFailure = $v;
            } elseif ('retry_on_status_codes' === $k) {
                $n->RetryOnStatusCodes = $v;
            } elseif ('retry_on' === $k) {
                $n->RetryOn = $v;
            } elseif ('RequestHeaders' === $k || 'request_headers' === $k) {
                $n->RequestHeaders = null === $v ? null : HTTPHeaderModifiers::jsonUnserialize($v);
            } elseif ('ResponseHeaders' === $k || 'response_headers' === $k) {
                $n->ResponseHeaders = null === $v ? null : HTTPHeaderModifiers::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ('' !== $this->Service) {
            $out->Service = $this->Service;
        }
        if ('' !== $this->ServiceSubset) {
            $out->ServiceSubset = $this->ServiceSubset;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->PrefixRewrite) {
            $out->PrefixRewrite = $this->PrefixRewrite;
        }
        if (0 !== $this->RequestTimeout->Nanoseconds()) {
            $out->RequestTimeout = (string)$this->RequestTimeout;
        }
        if (0 !== $this->IdleTimeout->Nanoseconds()) {
            $out->IdleTimeout = (string)$this->IdleTimeout;
        }
        if (0 !== $this->NumRetries) {
            $out->NumRetries = $this->NumRetries;
        }
        if ($this->RetryOnConnectFailure) {
            $out->RetryOnConnectFailure = $this->RetryOnConnectFailure;
        }
        if ([] !== $this->RetryOnStatusCodes) {
            $out->RetryOnStatusCodes = $this->RetryOnStatusCodes;
        }
        if ([] !== $this->RetryOn) {
            $out->RetryOn = $this->RetryOn;
        }
        if (null !== $this->RequestHeaders) {
            $out->RequestHeaders = $this->RequestHeaders;
        }
        if (null !== $this->ResponseHeaders) {
            $out->ResponseHeaders = $this->ResponseHeaders;
        }
        return $out;
    }
}
