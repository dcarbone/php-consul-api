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
 * class ServiceRouteHTTPMatch
 */
class ServiceRouteHTTPMatch extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_PATH_EXACT  => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_PATH_PREFIX => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_PATH_REGEX  => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_HEADER      => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => self::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_QUERY_PARAM => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ServiceRouteHTTPMatchQueryParam::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_METHODS     => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
    ];

    private const FIELD_PATH_EXACT  = 'PathExact';
    private const FIELD_PATH_PREFIX = 'PathPrefix';
    private const FIELD_PATH_REGEX  = 'PathRegex';
    private const FIELD_HEADER      = 'Header';
    private const FIELD_QUERY_PARAM = 'QueryParam';
    private const FIELD_METHODS     = 'Methods';

    /** @var string */
    public string $PathExact = '';
    /** @var string */
    public string $PathPrefix = '';
    /** @var string */
    public string $PathRegex = '';
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchHeader[] */
    public array $Header = [];
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchQueryParam[] */
    public array $QueryParam = [];
    /** @var string[] */
    public array $Methods = [];

    /**
     * @return string
     */
    public function getPathExact(): string
    {
        return $this->PathExact;
    }

    /**
     * @param string $PathExact
     * @return ServiceRouteHTTPMatch
     */
    public function setPathExact(string $PathExact): self
    {
        $this->PathExact = $PathExact;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathPrefix(): string
    {
        return $this->PathPrefix;
    }

    /**
     * @param string $PathPrefix
     * @return ServiceRouteHTTPMatch
     */
    public function setPathPrefix(string $PathPrefix): self
    {
        $this->PathPrefix = $PathPrefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathRegex(): string
    {
        return $this->PathRegex;
    }

    /**
     * @param string $PathRegex
     * @return ServiceRouteHTTPMatch
     */
    public function setPathRegex(string $PathRegex): self
    {
        $this->PathRegex = $PathRegex;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchHeader[]
     */
    public function getHeader(): array
    {
        return $this->Header;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchHeader[] $Header
     * @return ServiceRouteHTTPMatch
     */
    public function setHeader(array $Header): self
    {
        $this->Header = $Header;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchQueryParam[]
     */
    public function getQueryParam(): array
    {
        return $this->QueryParam;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchQueryParam[] $QueryParam
     * @return ServiceRouteHTTPMatch
     */
    public function setQueryParam(array $QueryParam): self
    {
        $this->QueryParam = $QueryParam;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->Methods;
    }

    /**
     * @param string[] $Methods
     * @return ServiceRouteHTTPMatch
     */
    public function setMethods(array $Methods): self
    {
        $this->Methods = $Methods;
        return $this;
    }
}
