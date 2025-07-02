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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;

class CookieConfig extends AbstractModel
{
    public bool $Session;
    public Time\Duration $TTL;
    public string $Path;

    public function __construct(
        bool $Session = false,
        null|string|int|float|\DateInterval|Time\Duration $TTL = null,
        string $Path = ''
    ) {
        $this->Session = $Session;
        $this->TTL = Time::Duration($TTL);
        $this->Path = $Path;
    }

    public function getSession(): bool
    {
        return $this->Session;
    }

    public function setSession(bool $Session): self
    {
        $this->Session = $Session;
        return $this;
    }

    public function getTTL(): Time\Duration
    {
        return $this->TTL;
    }

    public function setTTL(Time\Duration $TTL): self
    {
        $this->TTL = $TTL;
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('TTL' === $k) {
                $n->TTL = Time::Duration($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ($this->Session) {
            $out->Session = $this->Session;
        }
        if (($v = $this->TTL->Nanoseconds()) && 0 !== $v) {
            $out->TTL = $v;
        }
        if ('' !== $this->Path) {
            $out->Path = $this->Path;
        }
        return $out;
    }
}