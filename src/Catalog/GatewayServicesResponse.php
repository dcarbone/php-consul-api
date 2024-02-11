<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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

use DCarbone\PHPConsulAPI\AbstractValuedQueryResponse;
use DCarbone\PHPConsulAPI\UnmarshalledResponseInterface;

/**
 * Class GatewayServicesResponse
 */
class GatewayServicesResponse extends AbstractValuedQueryResponse implements UnmarshalledResponseInterface
{
    /** @var \DCarbone\PHPConsulAPI\Catalog\GatewayService[]|null */
    public ?array $GatewayServices = null;

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService[]|mixed|null
     */
    public function getValue(): mixed
    {
        return $this->GatewayServices;
    }

    /**
     * @param mixed $decodedData
     */
    public function unmarshalValue(mixed $decodedData): void
    {
        $this->GatewayServices = [];
        foreach ($decodedData as $service) {
            $this->GatewayServices[] = new GatewayService($service);
        }
    }
}
