<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

use DCarbone\PHPConsulAPI\AbstractValuedResponse;
use DCarbone\PHPConsulAPI\UnmarshalledResponseInterface;

class OperatorRaftConfigurationResponse extends AbstractValuedResponse implements UnmarshalledResponseInterface
{
    public ?RaftConfiguration $RaftConfiguration = null;

    public function getValue(): ?RaftConfiguration
    {
        return $this->RaftConfiguration;
    }

    public function unmarshalValue(mixed $decodedData): void
    {
        $this->RaftConfiguration = new RaftConfiguration($decodedData);
    }
}
