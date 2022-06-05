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
 * Class ExposePath
 */
class ExposePath extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_LISTENER_PORT   => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_PATH            => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_LOCAL_PORT_PATH => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_PROTOCOL        => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_LISTENER_PORT   = 'ListenerPort';
    private const FIELD_PATH            = 'Path';
    private const FIELD_LOCAL_PORT_PATH = 'LocalPortPath';
    private const FIELD_PROTOCOL        = 'Protocol';

    /** @var int */
    public int $ListenerPort = 0;
    /** @var string */
    public string $Path = '';
    /** @var int */
    public int $LocalPathPort = 0;
    /** @var string */
    public string $Protocol = '';
    /** @var bool */
    public bool $ParsedFromCheck = false;

    /**
     * @return int
     */
    public function getListenerPort(): int
    {
        return $this->ListenerPort;
    }

    /**
     * @param int $ListenerPort
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ExposePath
     */
    public function setListenerPort(int $ListenerPort): self
    {
        $this->ListenerPort = $ListenerPort;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->Path;
    }

    /**
     * @param string $Path
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ExposePath
     */
    public function setPath(string $Path): self
    {
        $this->Path = $Path;
        return $this;
    }

    /**
     * @return int
     */
    public function getLocalPathPort(): int
    {
        return $this->LocalPathPort;
    }

    /**
     * @param int $LocalPathPort
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ExposePath
     */
    public function setLocalPathPort(int $LocalPathPort): self
    {
        $this->LocalPathPort = $LocalPathPort;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    /**
     * @param string $Protocol
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ExposePath
     */
    public function setProtocol(string $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    /**
     * @return bool
     */
    public function isParsedFromCheck(): bool
    {
        return $this->ParsedFromCheck;
    }

    /**
     * @param bool $ParsedFromCheck
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ExposePath
     */
    public function setParsedFromCheck(bool $ParsedFromCheck): self
    {
        $this->ParsedFromCheck = $ParsedFromCheck;
        return $this;
    }
}
