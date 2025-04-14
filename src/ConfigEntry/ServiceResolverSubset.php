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

class ServiceResolverSubset extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_FILTER       => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_ONLY_PASSING => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
    ];

    private const FIELD_FILTER       = 'Filter';
    private const FIELD_ONLY_PASSING = 'OnlyPassing';

    public string $Filter;
    public bool $OnlyPassing;

    public function getFilter(): string
    {
        return $this->Filter;
    }

    public function setFilter(string $Filter): self
    {
        $this->Filter = $Filter;
        return $this;
    }

    public function isOnlyPassing(): bool
    {
        return $this->OnlyPassing;
    }

    public function setOnlyPassing(bool $OnlyPassing): self
    {
        $this->OnlyPassing = $OnlyPassing;
        return $this;
    }
}
