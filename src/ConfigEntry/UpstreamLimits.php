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

class UpstreamLimits extends AbstractModel
{
    public null|int $MaxConnections = null;
    public null|int $MaxPendingRequests = null;
    public null|int $MaxConcurrentRequests = null;

    public function __construct(
        null|int $MaxConnections = null,
        null|int $MaxPendingRequests = null,
        null|int $MaxConcurrentRequests = null,
    ) {
        $this->MaxConnections = $MaxConnections;
        $this->MaxPendingRequests = $MaxPendingRequests;
        $this->MaxConcurrentRequests = $MaxConcurrentRequests;
    }

    public function getMaxConnections(): ?int
    {
        return $this->MaxConnections;
    }

    public function setMaxConnections(?int $MaxConnections): self
    {
        $this->MaxConnections = $MaxConnections;
        return $this;
    }

    public function getMaxPendingRequests(): ?int
    {
        return $this->MaxPendingRequests;
    }

    public function setMaxPendingRequests(?int $MaxPendingRequests): self
    {
        $this->MaxPendingRequests = $MaxPendingRequests;
        return $this;
    }

    public function getMaxConcurrentRequests(): ?int
    {
        return $this->MaxConcurrentRequests;
    }

    public function setMaxConcurrentRequests(?int $MaxConcurrentRequests): self
    {
        $this->MaxConcurrentRequests = $MaxConcurrentRequests;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('max_connections' === $k) {
                $n->MaxConnections = $v;
            } elseif ('max_pending_requests' === $k) {
                $n->MaxPendingRequests = $v;
            } elseif ('max_concurrent_requests' === $k) {
                $n->MaxConcurrentRequests = $v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->MaxConnections = $this->MaxConnections;
        $out->MaxPendingRequests = $this->MaxPendingRequests;
        $out->MaxConcurrentRequests = $this->MaxConcurrentRequests;
        return $out;
    }
}
