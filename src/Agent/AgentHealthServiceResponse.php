<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\ResponseErrorTrait;

/**
 * Class AgentHealthServiceResponse
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentHealthServiceResponse implements \ArrayAccess
{
    use ResponseErrorTrait;

    /** @var string */
    public $AggregatedStatus = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceChecksInfo[]|null */
    public $AgentServiceChecksInfos = null;

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
    public function offsetExists($offset)
    {
        return is_int($offset) && 0 <= $offset && $offset < 3;
    }

    /**
     * @param mixed $offset
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceChecksInfo[]|\DCarbone\PHPConsulAPI\Error|string|null
     */
    public function offsetGet($offset)
    {
        if (0 === $offset) {
            return $this->AggregatedStatus;
        } elseif (1 === $offset) {
            return $this->AgentServiceChecksInfos;
        } elseif (2 === $offset) {
            return $this->Err;
        } else {
            throw new \OutOfBoundsException(sprintf('Offset %s does not exist', var_export($offset, true)));
        }
    }

    /**
     * @param $offset
     * @param $value
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException(sprintf('Cannot call %s on %s', __METHOD__, get_called_class()));
    }

    /**
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException(sprintf('Cannot call %s on %s', __METHOD__, get_called_class()));
    }
}