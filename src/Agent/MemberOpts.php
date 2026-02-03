<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

class MemberOpts extends AbstractType
{
    public bool $WAN;
    public string $Segment;
    public string $Filter;

    public function __construct(
        bool $WAN = false,
        string $Segment = '',
        string $Filter = '',
    ) {
        $this->WAN = $WAN;
        $this->Segment = $Segment;
        $this->Filter = $Filter;
}

    public function isWAN(): bool
    {
        return $this->WAN;
    }

    public function setWAN(bool $wan): self
    {
        $this->WAN = $wan;
        return $this;
    }

    public function getSegment(): string
    {
        return $this->Segment;
    }

    public function setSegment(string $segment): self
    {
        $this->Segment = $segment;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ($this->WAN) {
            $out->WAN = $this->WAN;
        }
        if ('' !== $this->Segment) {
            $out->Segment = $this->Segment;
        }
        if ('' !== $this->Filter) {
            $out->Filter = $this->Filter;
        }
        return $out;
    }
}
