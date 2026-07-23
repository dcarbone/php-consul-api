<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class IngressServiceConfig extends AbstractType
{
    public null|int $MaxConnections;
    public null|int $MaxPendingRequests;
    public null|int $MaxConcurrentRequests;
    public null|PassiveHealthCheck $PassiveHealthCheck;

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        null|int $MaxConnections = null,
        null|int $MaxPendingRequests = null,
        null|int $MaxConcurrentRequests = null,
        null|PassiveHealthCheck $PassiveHealthCheck = null,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->MaxConnections = $MaxConnections;
        $this->MaxPendingRequests = $MaxPendingRequests;
        $this->MaxConcurrentRequests = $MaxConcurrentRequests;
        $this->PassiveHealthCheck = $PassiveHealthCheck;
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
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('PassiveHealthCheck' === $k || 'passive_health_check' === $k) {
                $n->PassiveHealthCheck = null === $v ? null : PassiveHealthCheck::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->MaxConnections = $this->MaxConnections;
        $out->MaxPendingRequests = $this->MaxPendingRequests;
        $out->MaxConcurrentRequests = $this->MaxConcurrentRequests;
        if (null !== $this->PassiveHealthCheck) {
            $out->PassiveHealthCheck = $this->PassiveHealthCheck->jsonSerialize();
        }
        return $out;
    }
}
