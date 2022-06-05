<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

use DCarbone\PHPConsulAPI\AbstractValuedWriteResponse;
use DCarbone\PHPConsulAPI\UnmarshalledResponseInterface;

/**
 * Class OperatorAreaJoinResponse
 */
class OperatorAreaJoinResponse extends AbstractValuedWriteResponse implements UnmarshalledResponseInterface
{
    /** @var \DCarbone\PHPConsulAPI\Operator\AreaJoinResponse[]|null */
    public ?array $AreaJoinResponses = null;

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AreaJoinResponse[]|null
     */
    public function getValue()
    {
        return $this->AreaJoinResponses;
    }

    /**
     * @param mixed $decodedData
     */
    public function unmarshalValue($decodedData): void
    {
        $this->AreaJoinResponses = [];
        foreach ($decodedData as $area) {
            $this->AreaJoinResponses[] = new AreaJoinResponse($area);
        }
    }
}
