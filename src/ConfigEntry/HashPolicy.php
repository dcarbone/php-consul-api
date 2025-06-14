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

class HashPolicy extends AbstractModel
{
    public string $Field;
    public string $FieldValue;
    public null|CookieConfig $CookieConfig;
    public bool $SourceIP;
    public bool $Terminal;

    public function __construct(
        string $Field = '',
        string $FieldValue = '',
        null|CookieConfig $CookieConfig = null,
        bool $SourceIP = false,
        bool $Terminal = false
    ) {
        $this->Field = $Field;
        $this->FieldValue = $FieldValue;
        $this->CookieConfig = $CookieConfig;
        $this->SourceIP = $SourceIP;
        $this->Terminal = $Terminal;
    }

    public function getField(): string
    {
        return $this->Field;
    }

    public function setField(string $Field): self
    {
        $this->Field = $Field;
        return $this;
    }

    public function getFieldValue(): string
    {
        return $this->FieldValue;
    }

    public function setFieldValue(string $FieldValue): self
    {
        $this->FieldValue = $FieldValue;
        return $this;
    }

    public function getCookieConfig(): null|CookieConfig
    {
        return $this->CookieConfig;
    }

    public function setCookieConfig(null|CookieConfig $CookieConfig): self
    {
        $this->CookieConfig = $CookieConfig;
        return $this;
    }

    public function isSourceIP(): bool
    {
        return $this->SourceIP;
    }

    public function setSourceIP(bool $SourceIP): self
    {
        $this->SourceIP = $SourceIP;
        return $this;
    }

    public function isTerminal(): bool
    {
        return $this->Terminal;
    }

    public function setTerminal(bool $Terminal): self
    {
        $this->Terminal = $Terminal;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('field_value' === $k) {
                $n->FieldValue = $v;
            } elseif ('cookie_config' === $k) {
                $n->CookieConfig = CookieConfig::jsonUnserialize($v);
            } elseif ('source_ip' === $k) {
                $n->SourceIP = (bool)$v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        if ('' !== $this->Field) {
            $out->Field = $this->Field;
        }
        if ('' !== $this->FieldValue) {
            $out->FieldValue = $this->FieldValue;
        }
        if (null !== $this->CookieConfig) {
            $out->CookieConfig = $this->CookieConfig;
        }
        if ($this->SourceIP) {
            $out->SourceIP = $this->SourceIP;
        }
        if ($this->Terminal) {
            $out->Terminal = $this->Terminal;
        }
        return $out;
    }
}
