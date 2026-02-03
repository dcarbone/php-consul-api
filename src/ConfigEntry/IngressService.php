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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class IngressService extends AbstractType
{
    public string $Name;
    /** @var array<string> */
    public array $Hosts;
    public string $Namespace;
    public string $Partition;
    public null|GatewayServiceTLSConfig $TLS;
    public null|HTTPHeaderModifiers $RequestHeaders;
    public null|HTTPHeaderModifiers $ResponseHeaders;

    public null|int $MaxConnections;
    public null|int $MaxPendingRequests;
    public null|int $MaxConcurrentRequests;
    public null|PassiveHealthCheck $PassiveHealthCheck;

    public function __construct(
        string $Name = '',
        array $Hosts = [],
        string $Namespace = '',
        string $Partition = '',
        null|GatewayServiceTLSConfig $TLS = null,
        null|HTTPHeaderModifiers $RequestHeaders = null,
        null|HTTPHeaderModifiers $ResponseHeaders = null,
        null|int $MaxConnections = null,
        null|int $MaxPendingRequests = null,
        null|int $MaxConcurrentRequests = null,
        null|PassiveHealthCheck $PassiveHealthCheck = null
    ) {
        $this->Name = $Name;
        $this->setHosts(...$Hosts);
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        $this->TLS = $TLS;
        $this->RequestHeaders = $RequestHeaders;
        $this->ResponseHeaders = $ResponseHeaders;
        $this->MaxConnections = $MaxConnections;
        $this->MaxPendingRequests = $MaxPendingRequests;
        $this->MaxConcurrentRequests = $MaxConcurrentRequests;
        $this->PassiveHealthCheck = $PassiveHealthCheck;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getHosts(): array
    {
        return $this->Hosts;
    }

    public function setHosts(string ...$Hosts): self
    {
        $this->Hosts = $Hosts;
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

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public function getTLS(): null|GatewayServiceTLSConfig
    {
        return $this->TLS;
    }

    public function setTLS(null|GatewayServiceTLSConfig $TLS): self
    {
        $this->TLS = $TLS;
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

    public function getMaxConnections(): null|int
    {
        return $this->MaxConnections;
    }

    public function setMaxConnections(null|int $MaxConnections): self
    {
        $this->MaxConnections = $MaxConnections;
        return $this;
    }

    public function getMaxPendingRequests(): null|int
    {
        return $this->MaxPendingRequests;
    }

    public function setMaxPendingRequests(null|int $MaxPendingRequests): self
    {
        $this->MaxPendingRequests = $MaxPendingRequests;
        return $this;
    }

    public function getMaxConcurrentRequests(): null|int
    {
        return $this->MaxConcurrentRequests;
    }

    public function setMaxConcurrentRequests(null|int $MaxConcurrentRequests): self
    {
        $this->MaxConcurrentRequests = $MaxConcurrentRequests;
        return $this;
    }

    public function getPassiveHealthCheck(): null|PassiveHealthCheck
    {
        return $this->PassiveHealthCheck;
    }

    public function setPassiveHealthCheck(null|PassiveHealthCheck $PassiveHealthCheck): self
    {
        $this->PassiveHealthCheck = $PassiveHealthCheck;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach($decoded as $k => $v) {
            if ('RequestHeaders' === $k || 'request_headers' === $k) {
                $n->RequestHeaders = HTTPHeaderModifiers::jsonUnserialize($v, $n->RequestHeaders);
            } elseif ('ResponseHeaders' === $k || 'response_headers' === $k) {
                $n->ResponseHeaders = HTTPHeaderModifiers::jsonUnserialize($v, $n->ResponseHeaders);
            } elseif ('TLS' === $k) {
                $n->TLS = GatewayServiceTLSConfig::jsonUnserialize($v, $n->TLS);
            } elseif ('PassiveHealthCheck' === $k || 'passive_health_check' === $k) {
                $n->PassiveHealthCheck = PassiveHealthCheck::jsonUnserialize($v, $n->PassiveHealthCheck);
            } else {
                $n->{$k} = $v;
            }
        }
        return $into;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Name = $this->Name;
        $out->Hosts = $this->Hosts;
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if (null !== $this->TLS) {
            $out->TLS = $this->TLS;
        }
        if (null !== $this->RequestHeaders) {
            $out->RequestHeaders = $this->RequestHeaders;
        }
        if (null !== $this->ResponseHeaders) {
            $out->ResponseHeaders = $this->ResponseHeaders;
        }
        if (null !== $this->MaxConnections) {
            $out->MaxConnections = $this->MaxConnections;
        }
        if (null !== $this->MaxPendingRequests) {
            $out->MaxPendingRequests = $this->MaxPendingRequests;
        }
        if (null !== $this->MaxConcurrentRequests) {
            $out->MaxConcurrentRequests = $this->MaxConcurrentRequests;
        }
        if (null !== $this->PassiveHealthCheck) {
            $out->PassiveHealthCheck = $this->PassiveHealthCheck;
        }
        return $out;
    }
}
