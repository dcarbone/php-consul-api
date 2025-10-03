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

class InstanceLevelRateLimits extends AbstractType
{
    public int $RequestsPerSecond;
    public int $RequestsMaxBurst;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\InstanceLevelRouteRateLimits> */
    public array $Routes;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\InstanceLevelRouteRateLimits> $Routes
     */
    public function __construct(
        int $RequestsPerSecond = 0,
        int $RequestsMaxBurst = 0,
        array $Routes = []
    ) {
        $this->RequestsPerSecond = $RequestsPerSecond;
        $this->RequestsMaxBurst = $RequestsMaxBurst;
        $this->SetRoutes(...$Routes);
    }

    public function getRequestsPerSecond(): int
    {
        return $this->RequestsPerSecond;
    }

    public function setRequestsPerSecond(int $RequestsPerSecond): self
    {
        $this->RequestsPerSecond = $RequestsPerSecond;
        return $this;
    }

    public function getRequestsMaxBurst(): int
    {
        return $this->RequestsMaxBurst;
    }

    public function setRequestsMaxBurst(int $RequestsMaxBurst): self
    {
        $this->RequestsMaxBurst = $RequestsMaxBurst;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\InstanceLevelRouteRateLimits>
     */
    public function getRoutes(): array
    {
        return $this->Routes;
    }

    public function setRoutes(InstanceLevelRouteRateLimits ...$Routes): self
    {
        $this->Routes = $Routes;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('requests_per_second' === $k) {
                $n->RequestsPerSecond = $v;
            } elseif ('requests_max_burst' === $k) {
                $n->RequestsMaxBurst = $v;
            } elseif ('Routes' === $k) {
                $n->Routes = [];
                foreach ($v as $rv) {
                    $n->Routes[] = InstanceLevelRouteRateLimits::jsonUnserialize($rv);
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
        $out->RequestsPerSecond = $this->RequestsPerSecond;
        $out->RequestsMaxBurst = $this->RequestsMaxBurst;
        $out->Routes = $this->Routes;
        return $out;
    }
}
