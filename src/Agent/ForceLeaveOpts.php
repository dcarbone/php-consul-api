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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class ForceLeaveOpts extends AbstractType
{
    public bool $Prune;
    public bool $WAN;

    public function __construct(
        bool $Prune = false,
        bool $WAN = false,
    ) {
        $this->Prune = $Prune;
        $this->WAN = $WAN;
    }

    public function isPrune(): bool
    {
        return $this->Prune;
    }

    public function setPrune(bool $Prune): self
    {
        $this->Prune = $Prune;
        return $this;
    }

    public function isWAN(): bool
    {
        return $this->WAN;
    }

    public function setWAN(bool $WAN): self
    {
        $this->WAN = $WAN;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Prune = $this->Prune;
        $out->WAN = $this->WAN;
        return $out;
    }
}

