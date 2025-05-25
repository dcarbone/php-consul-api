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
    public array $Arguments;
    public string $ConsulVersion;
    public string $EnvoyVersion;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $Name = '',
        bool $Required = false,
        array|\stdClass $Arguments = [],
        string $ConsulVersion = '',
        string $EnvoyVersion = '',
    ) {
        $this->Name = $Name;
        $this->Required = $Required;
        $this->setArguments($Arguments);
        $this->ConsulVersion = $ConsulVersion;
        $this->EnvoyVersion = $EnvoyVersion;
        if (null !== $data && [] !== $data) {
            self::jsonUnserialize((object)$data, $this);
        }
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

    public function getArguments(): array
    {
        return $this->Arguments;
    }

    public function setArguments(array|\stdClass $Arguments): self
    {
        $this->Arguments = (array)$Arguments;
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('Arguments' === $k) {
                $n->setArguments($v);
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
        $out->Name = $this->Name;
        $out->Required = $this->Required;
        $out->Arguments = $this->Arguments;
        $out->ConsulVersion = $this->ConsulVersion;
        $out->EnvoyVersion = $this->EnvoyVersion;
        return $out;
    }
}
