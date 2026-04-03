<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

class OperatorHealthReply extends AbstractType
{
    public bool $Healthy;
    public int $FailureTolerance;
    /** @var array<ServerHealth> */
    public array $Servers;

    /**
     * @param array<ServerHealth> $Servers
     */
    public function __construct(
        bool $Healthy = false,
        int $FailureTolerance = 0,
        array $Servers = [],
    ) {
        $this->Healthy = $Healthy;
        $this->FailureTolerance = $FailureTolerance;
        $this->Servers = $Servers;
    }

    public function isHealthy(): bool
    {
        return $this->Healthy;
    }

    public function setHealthy(bool $Healthy): self
    {
        $this->Healthy = $Healthy;
        return $this;
    }

    public function getFailureTolerance(): int
    {
        return $this->FailureTolerance;
    }

    public function setFailureTolerance(int $FailureTolerance): self
    {
        $this->FailureTolerance = $FailureTolerance;
        return $this;
    }

    /**
     * @return array<ServerHealth>
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    /**
     * @param array<ServerHealth> $Servers
     */
    public function setServers(array $Servers): self
    {
        $this->Servers = $Servers;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Servers' === $k) {
                $n->Servers = [];
                foreach ($v as $sv) {
                    $n->Servers[] = ServerHealth::jsonUnserialize($sv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Healthy = $this->Healthy;
        $out->FailureTolerance = $this->FailureTolerance;
        $out->Servers = $this->Servers;
        return $out;
    }
}
