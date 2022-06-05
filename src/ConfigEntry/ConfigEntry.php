<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Interface ConfigEntry
 *
 * NOTE: I'm being a bit lazy here and relying on the case-insensitive
 *      nature of class methods to make implementations of this interface work.
 */
interface ConfigEntry
{
    public const INTERFACE_FIELDS = [
        self::FIELD_NAMESPACE         => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_META              => Transcoding::MAP_FIELD,
    ];

    public const FIELD_NAMESPACE = 'Namespace';
    public const FIELD_META      = 'Meta';

    /**
     * @return string
     */
    public function GetKind(): string;

    /**
     * @return string
     */
    public function GetName(): string;

    /**
     * @return string
     */
    public function GetNamespace(): string;

    /**
     * @return \DCarbone\PHPConsulAPI\FakeMap|null
     */
    public function GetMeta(): ?FakeMap;

    /**
     * @return int
     */
    public function GetCreateIndex(): int;

    /**
     * @return int
     */
    public function GetModifyIndex(): int;
}
