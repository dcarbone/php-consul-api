<?php

declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\FakeSlice;

/**
 * Class HealthChecks
 */
class HealthChecks extends FakeSlice
{
    /** @var string */
    protected string $containedClass = HealthCheck::class;

    /**
     * @return string
     */
    public function AggregatedStatus(): string
    {
        $passing = $warning = $critical = $maintenance = false;
        foreach ($this as $check) {
            /** @var \DCarbone\PHPConsulAPI\Health\HealthCheck $check */
            if (Consul::NodeMaint === $check->CheckID || 0 === strpos($check->CheckID, Consul::ServiceMaintPrefix)) {
                // TODO: Maybe just return maintenance right now...?
                $maintenance = true;
                continue;
            }
            switch ($check->Status) {
                case Consul::HealthPassing:
                    $passing = true;
                    break;
                case Consul::HealthWarning:
                    $warning = true;
                    break;
                case Consul::HealthCritical:
                    $critical = true;
                    break;

                default:
                    return '';
            }
        }

        if ($maintenance) {
            return Consul::HealthMaint;
        }
        if ($critical) {
            return Consul::HealthCritical;
        }
        if ($warning) {
            return Consul::HealthWarning;
        }
        if ($passing) {
            return Consul::HealthPassing;
        }
        return Consul::HealthPassing;
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\AbstractModel
     */
    protected function newChild(array $data): AbstractModel
    {
        return new HealthCheck($data);
    }
}
