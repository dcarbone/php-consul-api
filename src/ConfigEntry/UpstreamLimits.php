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
use DCarbone\PHPConsulAPI\Transcoding;

class UpstreamLimits extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_MAX_CONNECTIONS         => [
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_MAX_PENDING_REQUESTS    => [
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_MAX_CONCURRENT_REQUESTS => [
            Transcoding::FIELD_NULLABLE => true,
        ],
    ];

    private const FIELD_MAX_CONNECTIONS         = 'MaxConnections';
    private const FIELD_MAX_PENDING_REQUESTS    = 'MaxPendingRequests';
    private const FIELD_MAX_CONCURRENT_REQUESTS = 'MaxConcurrentRequests';

    public ?int $MaxConnections = null;
    public ?int $MaxPendingRequests = null;
    public ?int $MaxConcurrentRequests = null;

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
}
