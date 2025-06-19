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
use DCarbone\PHPConsulAPI\SimpleJsonUnserializeTrait;

class IntentionJWTClaimVerification extends AbstractModel
{
    use SimpleJsonUnserializeTrait;

    /** @var array<string> */
    public array $Path;
    public string $Value;

    /**
     * @param array<string> $Path
     */
    public function __construct(array $Path = [], string $Value = '')
    {
        $this->setPath(...$Path);
        $this->Value = $Value;
    }

    /**
     * @return array<string>
     */
    public function getPath(): array
    {
        return $this->Path;
    }

    public function setPath(string ...$Path): self
    {
        $this->Path = $Path;
        return $this;
    }

    public function getValue(): string
    {
        return $this->Value;
    }

    public function setValue(string $Value): self
    {
        $this->Value = $Value;
        return $this;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        if ([] !== $this->Path) {
            $out->Path = $this->Path;
        }
        if ('' !== $this->Value) {
            $out->Value = $this->Value;
        }
        return $out;
    }
}
