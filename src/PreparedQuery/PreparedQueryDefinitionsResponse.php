<?php

declare(strict_types=1);

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

namespace DCarbone\PHPConsulAPI\PreparedQuery;

use DCarbone\PHPConsulAPI\AbstractValuedQueryResponse;
use DCarbone\PHPConsulAPI\UnmarshalledResponseInterface;

class PreparedQueryDefinitionsResponse extends AbstractValuedQueryResponse implements UnmarshalledResponseInterface
{
    public ?array $PreparedQueryDefinitions = null;

    public function getValue(): ?array
    {
        return $this->PreparedQueryDefinitions;
    }

    public function unmarshalValue(mixed $decodedData): void
    {
        $this->PreparedQueryDefinitions = [];
        foreach ($decodedData as $datum) {
            $this->PreparedQueryDefinitions[] = new PreparedQueryDefinition($datum);
        }
    }
}
