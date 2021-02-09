<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class ExposeConfig
 */
class ExposeConfig extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_CHECKS => [
            Hydration::FIELD_TYPE     => Hydration::BOOLEAN,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_PATHS  => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => ExposePath::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
            Hydration::FIELD_NULLABLE   => true,
        ],
    ];

    private const FIELD_CHECKS = 'Checks';
    private const FIELD_PATHS  = 'Paths';

    /** @var bool|null */
    public ?bool $Checks = null;
    /** @var array|null */
    public ?array $Paths = null;

    /**
     * @return bool|null
     */
    public function getChecks(): ?bool
    {
        return $this->Checks;
    }

    /**
     * @param bool|null $Checks
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig
     */
    public function setChecks(?bool $Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPaths(): ?array
    {
        return $this->Paths;
    }

    /**
     * @param array|null $Paths
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig
     */
    public function setPaths(?array $Paths): self
    {
        $this->Paths = $Paths;
        return $this;
    }
}
