<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

trait ResponseValueBoolTrait
{
    public bool $Value = false;

    public function getValue(): bool
    {
        return $this->Value;
    }

    public function unmarshalValue(mixed $decodedData): void
    {
        if (\is_bool($decodedData)) {
            $this->Value = $decodedData;
            return;
        }
        if (\is_string($decodedData)) {
            $this->Value = Transcoding::TRUE === strtolower(trim($decodedData));
            return;
        }
        $this->Value = (bool)$decodedData;
    }

    public function __toString(): string
    {
        return $this->Value ? Transcoding::TRUE : Transcoding::FALSE;
    }
}
