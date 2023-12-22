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
    /** @var \DCarbone\PHPConsulAPI\FakeMap|null */
    public ?FakeMap $Arguments = null;
    /** @var string */
    public string $ConsulVersion = '';
    /** @var string */
    public string $EnvoyVersion = '';

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
    public function setName(string $Name): EnvoyExtension
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
    public function setRequired(bool $Required): EnvoyExtension
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
     * @param \DCarbone\PHPConsulAPI\FakeMap|null $Arguments
     * @return EnvoyExtension
     */
    public function setArguments(?FakeMap $Arguments): EnvoyExtension
    {
        $this->Arguments = $Arguments;
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
    public function setConsulVersion(string $ConsulVersion): EnvoyExtension
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
    public function setEnvoyVersion(string $EnvoyVersion): EnvoyExtension
    {
        $this->EnvoyVersion = $EnvoyVersion;
        return $this;
    }
}
