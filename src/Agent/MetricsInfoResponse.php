<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\AbstractValuedResponse;
use DCarbone\PHPConsulAPI\UnmarshalledResponseInterface;

/**
 * Class MetricsInfoResponse
 */
class MetricsInfoResponse extends AbstractValuedResponse implements UnmarshalledResponseInterface
{
    /** @var \DCarbone\PHPConsulAPI\Agent\MetricsInfo|null */
    public ?MetricsInfo $MetricsInfo = null;

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\MetricsInfo|null
     */
    public function getValue(): ?MetricsInfo
    {
        return $this->MetricsInfo;
    }

    /**
     * @param mixed $decodedData
     */
    public function unmarshalValue(mixed $decodedData): void
    {
        $this->MetricsInfo = new MetricsInfo((array)$decodedData);
    }
}
