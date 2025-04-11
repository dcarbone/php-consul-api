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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\Transcoding;

class EnvoyExtension extends AbstractModel
{
    public const FIELDS = [
        self::FIELD_ARGUMENTS => Transcoding::MAP_FIELD,
    ];

    private const FIELD_ARGUMENTS = 'Arguments';

    public string $Name = '';
    public bool $Required = false;
    public FakeMap $Arguments;
    public string $ConsulVersion = '';
    public string $EnvoyVersion = '';

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Arguments)) {
            $this->Arguments = new FakeMap(null);
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

    public function getArguments(): ?FakeMap
    {
        return $this->Arguments;
    }

    public function setArguments(array|FakeMap|\stdClass|null $Arguments): self
    {
        $this->Arguments = FakeMap::parse($Arguments);
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
}
