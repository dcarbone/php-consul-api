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

class EnvoyExtension extends AbstractModel
{
    public string $Name;
    public bool $Required;
    public null|\stdClass $Arguments;
    public string $ConsulVersion;
    public string $EnvoyVersion;

    /**
     * @param array<string,mixed>|null $data
     */
    public function __construct(
        string $Name = '',
        bool $Required = false,
        null|\stdClass $Arguments = null,
        string $ConsulVersion = '',
        string $EnvoyVersion = '',
    ) {
        $this->Name = $Name;
        $this->Required = $Required;
        $this->Arguments = $Arguments;
        $this->ConsulVersion = $ConsulVersion;
        $this->EnvoyVersion = $EnvoyVersion;
}

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function isRequired(): bool
    {
        return $this->Required;
    }

    public function setRequired(bool $Required): self
    {
        $this->Required = $Required;
        return $this;
    }

    public function getArguments(): null|\stdClass
    {
        return $this->Arguments;
    }

    public function setArguments(null|\stdClass $Arguments): self
    {
        $this->Arguments = $Arguments;
        return $this;
    }

    public function getConsulVersion(): string
    {
        return $this->ConsulVersion;
    }

    public function setConsulVersion(string $ConsulVersion): self
    {
        $this->ConsulVersion = $ConsulVersion;
        return $this;
    }

    public function getEnvoyVersion(): string
    {
        return $this->EnvoyVersion;
    }

    public function setEnvoyVersion(string $EnvoyVersion): self
    {
        $this->EnvoyVersion = $EnvoyVersion;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Name = $this->Name;
        $out->Required = $this->Required;
        $out->Arguments = $this->Arguments;
        $out->ConsulVersion = $this->ConsulVersion;
        $out->EnvoyVersion = $this->EnvoyVersion;
        return $out;
    }
}
