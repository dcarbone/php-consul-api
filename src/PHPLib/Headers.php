<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PHPLib;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

class Headers extends Values
{
    /**
     * @param array<string,array<string>> $values
     */
    public static function fromArray(array $values): self
    {
        $out = new self();
        foreach ($values as $header => $items) {
            $out->add((string)$header, ...(array)$items);
        }
        return $out;
    }

    protected function findKey(string $key): null|string
    {
        foreach (array_keys($this->values) as $storedKey) {
            if (0 === strcasecmp((string)$storedKey, $key)) {
                return $storedKey;
            }
        }

        return null;
    }
}
