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

use DCarbone\PHPConsulAPI\AbstractResponse;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\ErrorContainer;

class AgentHealthServiceResponse extends AbstractResponse
{
    use ErrorContainer;

    public string $AggregatedStatus;
    public null|AgentServiceChecksInfo $AgentServiceChecksInfo;

    public function __construct(
        string $aggregatedStatus,
        null|\stdClass $checksInfo,
        null|Error $err
    ) {
        $this->AggregatedStatus = $aggregatedStatus;
        if (null !== $checksInfo) {
            $checksInfo = AgentServiceChecksInfo::jsonUnserialize($checksInfo);
        }
        $this->AgentServiceChecksInfo = $checksInfo;
        $this->Err = $err;
    }

    public function getAggregatedStatus(): string
    {
        return $this->AggregatedStatus;
    }

    public function getAgentServiceChecksInfos(): null|AgentServiceChecksInfo
    {
        return $this->AgentServiceChecksInfo;
    }

    public function offsetExists(mixed $offset): bool
    {
        return is_int($offset) && 0 <= $offset && $offset < 3;
    }

    public function offsetGet(mixed $offset): string|AgentServiceChecksInfo|Error|null
    {
        if (0 === $offset) {
            return $this->AggregatedStatus;
        }
        if (1 === $offset) {
            return $this->AgentServiceChecksInfo;
        }
        if (2 === $offset) {
            return $this->Err;
        }
        throw $this->_newOutOfRangeException($offset);
    }
}
