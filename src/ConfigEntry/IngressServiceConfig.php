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

use DCarbone\PHPConsulAPI\AbstractModel;

class IngressServiceConfig extends AbstractModel
{
    public null|int $MaxConnections;
    public null|int $MaxPendingRequests;
    public null|int $MaxConcurrentRequests;
    public null|PassiveHealthCheck $PassiveHealthCheck;

    public function __construct(
        null|int $MaxConnections = null,
        null|int $MaxPendingRequests = null,
        null|int $MaxConcurrentRequests = null,
        null|PassiveHealthCheck $PassiveHealthCheck = null,
    ) {
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('PassiveHealthCheck' === $k || 'passive_health_check' === $k) {
                $n->PassiveHealthCheck = null === $v ? null : PassiveHealthCheck::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->MaxConnections = $this->MaxConnections;
        $out->MaxPendingRequests = $this->MaxPendingRequests;
        $out->MaxConcurrentRequests = $this->MaxConcurrentRequests;
        if (null !== $this->PassiveHealthCheck) {
            $out->PassiveHealthCheck = $this->PassiveHealthCheck->jsonSerialize();
        }
        return $out;
    }
}
