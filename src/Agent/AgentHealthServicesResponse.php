<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\AbstractResponse;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\ErrorContainer;

/**
 * Class AgentHealthServiceResponse
 */
class AgentHealthServicesResponse extends AbstractResponse
{
    use ErrorContainer;

    /** @var string */
    public string $AggregatedStatus = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceChecksInfo[]|null */
    public ?array $AgentServiceChecksInfos = null;

    /**
     * AgentHealthServiceResponse constructor.
     * @param string $aggregatedStatus
     * @param array|null $checkInfos
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(string $aggregatedStatus, ?array $checkInfos, ?Error $err)
    {
        $this->AggregatedStatus = $aggregatedStatus;
        if (null !== $checkInfos) {
            $this->AgentServiceChecksInfos = [];
            foreach ($checkInfos as $checkInfo) {
                $this->AgentServiceChecksInfos[] = new AgentServiceChecksInfo($checkInfo);
            }
        }
        $this->Err = $err;
    }

    /**
     * @return string
     */
    public function getAggregatedStatus(): string
    {
        return $this->AggregatedStatus;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceChecksInfo[]|null
     */
    public function getAgentServiceChecksInfos(): ?array
    {
        return $this->AgentServiceChecksInfos;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return \is_int($offset) && 0 <= $offset && $offset < 3;
    }

    /**
     * @param mixed $offset
     * @return array|\DCarbone\PHPConsulAPI\Agent\AgentServiceChecksInfo[]|\DCarbone\PHPConsulAPI\Error|mixed|string|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (0 === $offset) {
            return $this->AggregatedStatus;
        }
        if (1 === $offset) {
            return $this->AgentServiceChecksInfos;
        }
        if (2 === $offset) {
            return $this->Err;
        }
        throw $this->_newOutOfRangeException($offset);
    }
}
