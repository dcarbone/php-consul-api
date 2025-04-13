<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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
use DCarbone\PHPConsulAPI\Health\HealthChecks;

class AgentServiceChecksInfo extends AbstractModel
{
    public string $AggregatedStatus;
    public null|AgentService $Service;
    public HealthChecks $Checks;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $AggregatedStatus = '',
        null|AgentService $Service = null,
        null|HealthChecks $Checks = null,
    ) {
        $this->AggregatedStatus = $AggregatedStatus;
        $this->Service = $Service;
        $this->Checks = $Checks ?? new HealthChecks();
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
        }
    }

    public function getAggregatedStatus(): string
    {
        return $this->AggregatedStatus;
    }

    public function setAggregatedStatus(string $AggregatedStatus): self
    {
        $this->AggregatedStatus = $AggregatedStatus;
        return $this;
    }

    public function getService(): null|AgentService
    {
        return $this->Service;
    }

    public function setService(null|AgentService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getChecks(): HealthChecks
    {
        return $this->Checks;
    }

    public function setChecks(HealthChecks $Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new static();
        foreach ($decoded as $k => $v) {
            if ('Checks' === $k) {
                $n->Checks = HealthChecks::jsonUnserialize($v);
            } elseif ('Service' === $k) {
                $n->Service = null === $v ? null : AgentService::jsonUnserialize($v);
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
        $out->AggregatedStatus = $this->AggregatedStatus;
        $out->Service = $this->Service;
        $out->Checks = $this->Checks;
        return $out;
    }

    public function __toString(): string
    {
        return $this->AggregatedStatus;
    }
}
