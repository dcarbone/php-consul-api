<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Health;

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class ServiceEntry
 */
class ServiceEntry extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SERVICE => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => AgentService::class,
        ],
        self::FIELD_CHECKS  => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => HealthChecks::class,
        ],
    ];
    private const FIELD_SERVICE = 'Service';
    private const FIELD_CHECKS  = 'Checks';

    /** @var string */
    public string $Node = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService|null */
    public ?AgentService $Service = null;
    /** @var \DCarbone\PHPConsulAPI\Health\HealthChecks|null */
    public ?HealthChecks $Checks = null;

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->Node;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService|null
     */
    public function getService(): ?AgentService
    {
        return $this->Service;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthChecks|null
     */
    public function getChecks(): ?HealthChecks
    {
        return $this->Checks;
    }
}
