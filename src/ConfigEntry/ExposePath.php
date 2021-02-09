<?php declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class ExposePath
 */
class ExposePath extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_LISTENER_PORT   => [
            Hydration::FIELD_TYPE     => Hydration::INTEGER,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_PATH            => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_LOCAL_PORT_PATH => [
            Hydration::FIELD_TYPE     => Hydration::INTEGER,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_PROTOCOL        => [
            Hydration::FIELD_TYPE     => Hydration::STRING,
            Hydration::FIELD_NULLABLE => true,
        ],
    ];

    private const FIELD_LISTENER_PORT   = 'ListenerPort';
    private const FIELD_PATH            = 'Path';
    private const FIELD_LOCAL_PORT_PATH = 'LocalPortPath';
    private const FIELD_PROTOCOL        = 'Protocol';

    /** @var int|null */
    public ?int $ListenerPort = null;
    /** @var string|null */
    public ?string $Path = null;
    /** @var int|null */
    public ?int $LocalPathPort = null;
    /** @var string|null */
    public ?string $Protocol = null;
    /** @var bool */
    public bool $ParsedFromCheck = false;

    /**
     * @return int|null
     */
    public function getListenerPort(): ?int
    {
        return $this->ListenerPort;
    }

    /**
     * @param int|null $ListenerPort
     * @return \DCarbone\PHPConsulAPI\Agent\ExposePath
     */
    public function setListenerPort(?int $ListenerPort): self
    {
        $this->ListenerPort = $ListenerPort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->Path;
    }

    /**
     * @param string|null $Path
     * @return \DCarbone\PHPConsulAPI\Agent\ExposePath
     */
    public function setPath(?string $Path): self
    {
        $this->Path = $Path;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLocalPathPort(): ?int
    {
        return $this->LocalPathPort;
    }

    /**
     * @param int|null $LocalPathPort
     * @return \DCarbone\PHPConsulAPI\Agent\ExposePath
     */
    public function setLocalPathPort(?int $LocalPathPort): self
    {
        $this->LocalPathPort = $LocalPathPort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProtocol(): ?string
    {
        return $this->Protocol;
    }

    /**
     * @param string|null $Protocol
     * @return \DCarbone\PHPConsulAPI\Agent\ExposePath
     */
    public function setProtocol(?string $Protocol): self
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
     * @return \DCarbone\PHPConsulAPI\Agent\ExposePath
     */
    public function setParsedFromCheck(bool $ParsedFromCheck): self
    {
        $this->ParsedFromCheck = $ParsedFromCheck;
        return $this;
    }
}
