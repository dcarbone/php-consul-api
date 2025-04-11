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

class MapResponse extends AbstractValuedResponse implements UnmarshalledResponseInterface
{
    public null|array $Map = null;

    public function getValue(): null|array
    {
        return $this->Map;
    }

    public function unmarshalValue(mixed $decodedData): void
    {
        if (null === $decodedData) {
            $this->Map = null;
            return;
        }
        $this->Map = (array)$decodedData;
    }
}
