<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ServiceRouteHTTPMatchHeader
 */
class ServiceRouteHTTPMatchHeader extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_PRESENT => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
        self::FIELD_EXACT   => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_PREFIX  => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_SUFFIX  => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_REGEX   => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_INVERT  => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
    ];

    private const FIELD_PRESENT = 'Present';
    private const FIELD_EXACT   = 'Exact';
    private const FIELD_PREFIX  = 'Prefix';
    private const FIELD_SUFFIX  = 'Suffix';
    private const FIELD_REGEX   = 'Regex';
    private const FIELD_INVERT  = 'Invert';

    /** @var string */
    public string $Name = '';
    /** @var bool */
    public bool $Present = false;
    /** @var string */
    public string $Exact = '';
    /** @var string */
    public string $Prefix = '';
    /** @var string */
    public string $Suffix = '';
    /** @var string */
    public string $Regex = '';
    /** @var bool|string */
    public bool $Invert = false;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return ServiceRouteHTTPMatchHeader
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPresent(): bool
    {
        return $this->Present;
    }

    /**
     * @param bool $Present
     * @return ServiceRouteHTTPMatchHeader
     */
    public function setPresent(bool $Present): self
    {
        $this->Present = $Present;
        return $this;
    }

    /**
     * @return string
     */
    public function getExact(): string
    {
        return $this->Exact;
    }

    /**
     * @param string $Exact
     * @return ServiceRouteHTTPMatchHeader
     */
    public function setExact(string $Exact): self
    {
        $this->Exact = $Exact;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->Prefix;
    }

    /**
     * @param string $Prefix
     * @return ServiceRouteHTTPMatchHeader
     */
    public function setPrefix(string $Prefix): self
    {
        $this->Prefix = $Prefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->Suffix;
    }

    /**
     * @param string $Suffix
     * @return ServiceRouteHTTPMatchHeader
     */
    public function setSuffix(string $Suffix): self
    {
        $this->Suffix = $Suffix;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegex(): string
    {
        return $this->Regex;
    }

    /**
     * @param string $Regex
     * @return ServiceRouteHTTPMatchHeader
     */
    public function setRegex(string $Regex): self
    {
        $this->Regex = $Regex;
        return $this;
    }

    /**
     * @return bool|string
     */
    public function getInvert(): bool|string
    {
        return $this->Invert;
    }

    /**
     * @param bool|string $Invert
     * @return ServiceRouteHTTPMatchHeader
     */
    public function setInvert(bool|string $Invert): static
    {
        $this->Invert = $Invert;
        return $this;
    }
}
