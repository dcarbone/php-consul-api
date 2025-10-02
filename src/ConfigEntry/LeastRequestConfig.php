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

class LeastRequestConfig extends AbstractModel
{
    public int $ChoiceCount;

    public function __construct(int $ChoiceCount = 0)
    {
        $this->ChoiceCount = $ChoiceCount;
    }

    public function getChoiceCount(): int
    {
        return $this->ChoiceCount;
    }

    public function setChoiceCount(int $ChoiceCount): self
    {
        $this->ChoiceCount = $ChoiceCount;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('choice_count' === $k) {
                $n->ChoiceCount = $v;
            } else {
                $n->{$k}= $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (0 !== $this->ChoiceCount) {
            $out->ChoiceCount = $this->ChoiceCount;
        }
        return $out;
    }
}