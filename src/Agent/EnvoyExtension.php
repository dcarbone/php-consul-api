<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2023 Daniel Carbone (daniel.p.carbone@gmail.com)

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
    protected const FIELDS = [
        self::FIELD_ARGUMENTS => Transcoding::MAP_FIELD,
    ];

    private const FIELD_ARGUMENTS = 'Arguments';

    /** @var string */
    public string $Name = '';
    /** @var bool */
    public bool $Required = false;
    /** @var \DCarbone\PHPConsulAPI\FakeMap */
    public FakeMap $Arguments;
    /** @var string */
    public string $ConsulVersion = '';
    /** @var string */
    public string $EnvoyVersion = '';

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Arguments)) {
            $this->Arguments = new FakeMap(null);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return EnvoyExtension
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->Required;
    }

    /**
     * @param bool $Required
     * @return EnvoyExtension
     */
    public function setRequired(bool $Required): self
    {
        $this->Required = $Required;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\FakeMap|null
     */
    public function getArguments(): ?FakeMap
    {
        return $this->Arguments;
    }

    /**
     * @param array|\DCarbone\PHPConsulAPI\FakeMap|\stdClass|null $Arguments
     * @return EnvoyExtension
     */
    public function setArguments(array|FakeMap|\stdClass|null $Arguments): self
    {
        $this->Arguments = FakeMap::parse($Arguments);
        return $this;
    }

    /**
     * @return string
     */
    public function getConsulVersion(): string
    {
        return $this->ConsulVersion;
    }

    /**
     * @param string $ConsulVersion
     * @return EnvoyExtension
     */
    public function setConsulVersion(string $ConsulVersion): self
    {
        $this->ConsulVersion = $ConsulVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvoyVersion(): string
    {
        return $this->EnvoyVersion;
    }

    /**
     * @param string $EnvoyVersion
     * @return EnvoyExtension
     */
    public function setEnvoyVersion(string $EnvoyVersion): self
    {
        $this->EnvoyVersion = $EnvoyVersion;
        return $this;
    }
}
