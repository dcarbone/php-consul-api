<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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

class Weights extends AbstractType
{
    public int $Passing;
    public int $Warning;

    public function __construct(int $Passing = 0, int $Warning = 0)
    {
        $this->Passing = $Passing;
        $this->Warning = $Warning;
    }

    public function getPassing(): int
    {
        return $this->Passing;
    }

    public function setPassing(int $Passing): self
    {
        $this->Passing = $Passing;
        return $this;
    }

    public function getWarning(): int
    {
        return $this->Warning;
    }

    public function setWarning(int $Warning): self
    {
        $this->Warning = $Warning;
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
        $out->Passing = $this->Passing;
        $out->Warning = $this->Warning;
        return $out;
    }
}
