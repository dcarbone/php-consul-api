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

class InstanceLevelRouteRateLimits extends AbstractType
{
    public string $PathExact;
    public string $PathPrefix;
    public string $PathRegex;
    public int $RequestsPerSecond;
    public int $RequestsMaxBurst;

    public function __construct(
        string $PathExact = '',
        string $PathPrefix = '',
        string $PathRegex = '',
        int $RequestsPerSecond = 0,
        int $RequestsMaxBurst = 0
    ) {
        $this->PathExact = $PathExact;
        $this->PathPrefix = $PathPrefix;
        $this->PathRegex = $PathRegex;
        $this->RequestsPerSecond = $RequestsPerSecond;
        $this->RequestsMaxBurst = $RequestsMaxBurst;
    }

    public function getPathExact(): string
    {
        return $this->PathExact;
    }

    public function setPathExact(string $PathExact): self
    {
        $this->PathExact = $PathExact;
        return $this;
    }

    public function getPathPrefix(): string
    {
        return $this->PathPrefix;
    }

    public function setPathPrefix(string $PathPrefix): self
    {
        $this->PathPrefix = $PathPrefix;
        return $this;
    }

    public function getPathRegex(): string
    {
        return $this->PathRegex;
    }

    public function setPathRegex(string $PathRegex): self
    {
        $this->PathRegex = $PathRegex;
        return $this;
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('path_exact' === $k) {
                $n->PathExact = $v;
            } elseif ('path_prefix' === $k) {
                $n->PathPrefix = $v;
            } elseif ('path_regex' === $k) {
                $n->PathRegex = $v;
            } elseif ('requests_per_second' === $k) {
                $n->RequestsPerSecond = (int)$v;
            } elseif ('requests_max_burst' === $k) {
                $n->RequestsMaxBurst = (int)$v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->PathExact = $this->PathExact;
        $out->PathPrefix = $this->PathPrefix;
        $out->PathRegex = $this->PathRegex;
        $out->RequestsPerSecond = $this->RequestsPerSecond;
        $out->RequestsMaxBurst = $this->RequestsMaxBurst;
        return $out;
    }
}
