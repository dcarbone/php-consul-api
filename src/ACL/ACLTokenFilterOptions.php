<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class ACLTokenFilterOptions extends AbstractType
{
    public string $AuthMethod;
    public string $Policy;
    public string $Role;
    public string $ServiceName;

    public function __construct(
        string $AuthMethod = '',
        string $Policy = '',
        string $Role = '',
        string $ServiceName = '',
    ) {
        $this->AuthMethod = $AuthMethod;
        $this->Policy = $Policy;
        $this->Role = $Role;
        $this->ServiceName = $ServiceName;
    }

    public function getAuthMethod(): string
    {
        return $this->AuthMethod;
    }

    public function setAuthMethod(string $AuthMethod): self
    {
        $this->AuthMethod = $AuthMethod;
        return $this;
    }

    public function getPolicy(): string
    {
        return $this->Policy;
    }

    public function setPolicy(string $Policy): self
    {
        $this->Policy = $Policy;
        return $this;
    }

    public function getRole(): string
    {
        return $this->Role;
    }

    public function setRole(string $Role): self
    {
        $this->Role = $Role;
        return $this;
    }

    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    public function setServiceName(string $ServiceName): self
    {
        $this->ServiceName = $ServiceName;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ('' !== $this->AuthMethod) {
            $out->AuthMethod = $this->AuthMethod;
        }
        if ('' !== $this->Policy) {
            $out->Policy = $this->Policy;
        }
        if ('' !== $this->Role) {
            $out->Role = $this->Role;
        }
        if ('' !== $this->ServiceName) {
            $out->ServiceName = $this->ServiceName;
        }
        return $out;
    }
}

