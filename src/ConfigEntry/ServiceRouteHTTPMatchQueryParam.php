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
use DCarbone\PHPConsulAPI\Transcoding;

class ServiceRouteHTTPMatchQueryParam extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_PRESENT => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
        self::FIELD_EXACT   => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_REGEX   => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_PRESENT = 'Present';
    private const FIELD_EXACT   = 'Exact';
    private const FIELD_REGEX   = 'Regex';

    public string $Name;
    public bool $Present;
    public string $Exact;
    public string $Regex;

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function isPresent(): bool
    {
        return $this->Present;
    }

    public function setPresent(bool $Present): self
    {
        $this->Present = $Present;
        return $this;
    }

    public function getExact(): string
    {
        return $this->Exact;
    }

    public function setExact(string $Exact): self
    {
        $this->Exact = $Exact;
        return $this;
    }

    public function getRegex(): string
    {
        return $this->Regex;
    }

    public function setRegex(string $Regex): self
    {
        $this->Regex = $Regex;
        return $this;
    }
}
