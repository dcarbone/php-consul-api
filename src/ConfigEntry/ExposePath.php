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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class ExposePath extends AbstractType
{
    public int $ListenerPort;
    public string $Path;
    public int $LocalPathPort;
    public string $Protocol;
    public bool $ParsedFromCheck;

    /**
     * @param array<string,mixed>|null $data
     */
    public function __construct(
        int $ListenerPort = 0,
        string $Path = '',
        int $LocalPathPort = 0,
        string $Protocol = '',
        bool $ParsedFromCheck = false
    ) {
        $this->ListenerPort = $ListenerPort;
        $this->Path = $Path;
        $this->LocalPathPort = $LocalPathPort;
        $this->Protocol = $Protocol;
        $this->ParsedFromCheck = $ParsedFromCheck;
}

    public function getListenerPort(): int
    {
        return $this->ListenerPort;
    }

    public function setListenerPort(int $ListenerPort): self
    {
        $this->ListenerPort = $ListenerPort;
        return $this;
    }

    public function getPath(): string
    {
        return $this->Path;
    }

    public function setPath(string $Path): self
    {
        $this->Path = $Path;
        return $this;
    }

    public function getLocalPathPort(): int
    {
        return $this->LocalPathPort;
    }

    public function setLocalPathPort(int $LocalPathPort): self
    {
        $this->LocalPathPort = $LocalPathPort;
        return $this;
    }

    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    public function setProtocol(string $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    public function isParsedFromCheck(): bool
    {
        return $this->ParsedFromCheck;
    }

    public function setParsedFromCheck(bool $ParsedFromCheck): self
    {
        $this->ParsedFromCheck = $ParsedFromCheck;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $n = null): self
    {
        $n = $n ?? new self();
        foreach ($decoded as $k => $v) {
            if ('listener_port' === $k) {
                $n->ListenerPort = $v;
            } elseif ('local_path_port' === $k) {
                $n->LocalPathPort = $v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (0 !== $this->ListenerPort) {
            $out->ListenerPort = $this->ListenerPort;
        }
        if ('' !== $this->Path) {
            $out->Path = $this->Path;
        }
        if (0 !== $this->LocalPathPort) {
            $out->LocalPathPort = $this->LocalPathPort;
        }
        if ('' !== $this->Protocol) {
            $out->Protocol = $this->Protocol;
        }
        if ($this->ParsedFromCheck) {
            $out->ParsedFromCheck = true;
        }
        return $out;
    }
}
