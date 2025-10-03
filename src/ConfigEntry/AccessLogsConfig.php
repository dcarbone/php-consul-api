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

class AccessLogsConfig extends AbstractType
{
    public bool $Enabled;
    public bool $DisableListenerLogs;
    public LogSinkType $Type;
    public string $Path;
    public string $JSONFormat;
    public string $TextFormat;

    public function __construct(
        bool $Enabled = false,
        bool $DisableListenerLogs = false,
        string|LogSinkType $Type = LogSinkType::Default,
        string $Path = '',
        string $JSONFormat = '',
        string $TextFormat = '',
    ) {
        $this->Enabled = $Enabled;
        $this->DisableListenerLogs = $DisableListenerLogs;
        $this->Type = $Type instanceof LogSinkType ? $Type : LogSinkType::from($Type);
        $this->Path = $Path;
        $this->JSONFormat = $JSONFormat;
        $this->TextFormat = $TextFormat;
    }

    public function isEnabled(): bool
    {
        return $this->Enabled;
    }

    public function setEnabled(bool $Enabled): self
    {
        $this->Enabled = $Enabled;
        return $this;
    }

    public function isDisableListenerLogs(): bool
    {
        return $this->DisableListenerLogs;
    }

    public function setDisableListenerLogs(bool $DisableListenerLogs): self
    {
        $this->DisableListenerLogs = $DisableListenerLogs;
        return $this;
    }

    public function getType(): LogSinkType
    {
        return $this->Type;
    }

    public function setType(string|LogSinkType $Type): self
    {
        $this->Type = $Type instanceof LogSinkType ? $Type : LogSinkType::from($Type);
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

    public function getJSONFormat(): string
    {
        return $this->JSONFormat;
    }

    public function setJSONFormat(string $JSONFormat): self
    {
        $this->JSONFormat = $JSONFormat;
        return $this;
    }

    public function getTextFormat(): string
    {
        return $this->TextFormat;
    }

    public function setTextFormat(string $TextFormat): self
    {
        $this->TextFormat = $TextFormat;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): static
    {
        $n = new static();
        foreach ($decoded as $k => $v) {
            if ('Type' === $k) {
                $n->setType($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ($this->Enabled) {
            $out->Enabled = $this->Enabled;
        }
        if ($this->DisableListenerLogs) {
            $out->DisableListenerLogs = $this->DisableListenerLogs;
        }
        if ($this->Type !== LogSinkType::Default) {
            $out->Type = $this->Type->value;
        }
        if ('' !== $this->Path) {
            $out->Path = $this->Path;
        }
        if ('' !== $this->JSONFormat) {
            $out->JSONFormat = $this->JSONFormat;
        }
        if ('' !== $this->TextFormat) {
            $out->TextFormat = $this->TextFormat;
        }
        return $out;
    }
}
