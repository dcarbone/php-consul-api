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
    /** @var array<string,mixed> */
    public array $Arguments;
    public string $ConsulVersion;
    public string $EnvoyVersion;

    /**
     * @param array<string,mixed> $Arguments
     */
    public function __construct(
        string $Name = '',
        bool $Required = false,
        array $Arguments = [],
        string $ConsulVersion = '',
        string $EnvoyVersion = '',
    ) {
        $this->Name = $Name;
        $this->Required = $Required;
        $this->setArguments($Arguments);
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

    /**
     * @return null|array<string,mixed>
     */
    public function getArguments(): null|array
    {
        return $this->Arguments ?? null;
    }

    public function setArgument(string $k, mixed $v): self
    {
        if (!isset($this->Arguments)) {
            $this->Arguments = [];
        }
        $this->Arguments[$k] = $v;
        return $this;
    }

    /**
     * @param \stdClass|array<string,mixed>|null $Arguments
     */
    public function setArguments(null|\stdClass|array $Arguments): self
    {
        if (null === $Arguments) {
            unset($this->Arguments);
            return $this;
        }
        $this->Arguments = [];
        foreach ($Arguments as $k => $v) {
            $this->setArgument($k, $v);
        }
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
        $out = $this->_startJsonSerialize();
        $out->Name = $this->Name;
        $out->Required = $this->Required;
        $out->Arguments = $this->getArguments();
        $out->ConsulVersion = $this->ConsulVersion;
        $out->EnvoyVersion = $this->EnvoyVersion;
        return $out;
    }
}
