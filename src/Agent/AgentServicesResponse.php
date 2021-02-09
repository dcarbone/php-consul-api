<?php declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\HydratedResponseInterface;

/**
 * Class AgentServicesResponse
 */
class AgentServicesResponse extends AbstractValuedResponse implements HydratedResponseInterface
{
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService[]|null */
    public ?array $Services = null;

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService[]|null
     */
    public function getValue()
    {
        return $this->Services;
    }

    /**
     * @param mixed $decodedData
     */
    public function hydrateValue($decodedData): void
    {
        $this->Services = [];
        foreach ($decodedData as $k => $service) {
            $this->Services[$k] = new AgentService($service);
        }
    }
}
