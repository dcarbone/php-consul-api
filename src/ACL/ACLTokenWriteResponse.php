<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\PHPConsulAPI\AbstractValuedWriteResponse;
use DCarbone\PHPConsulAPI\UnmarshalledResponseInterface;

class ACLTokenWriteResponse extends AbstractValuedWriteResponse implements UnmarshalledResponseInterface
{
    public null|ACLToken $ACLToken = null;

    public function getValue(): ?ACLToken
    {
        return $this->ACLToken;
    }

    public function unmarshalValue(mixed $decodedData): void
    {
        if (null === $decodedData) {
            $this->ACLToken = null;
            return;
        }
        $this->ACLToken = ACLToken::jsonUnserialize($decodedData);
    }
}
