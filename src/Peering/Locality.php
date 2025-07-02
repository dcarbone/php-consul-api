<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Peering;

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

class Locality extends AbstractModel
{
    public string $Region;
    public string $Zone;

    public function __construct(string $Region = '', string $Zone = '')
    {
        $this->Region = $Region;
        $this->Zone = $Zone;
    }

    public function getRegion(): string
    {
        return $this->Region;
    }

    public function setRegion(string $Region): self
    {
        $this->Region = $Region;
        return $this;
    }

    public function getZone(): string
    {
        return $this->Zone;
    }

    public function setZone(string $Zone): self
    {
        $this->Zone = $Zone;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): static
    {
        $n = new static();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Region = $this->Region;
        $out->Zone = $this->Zone;
        return $out;
    }
}
