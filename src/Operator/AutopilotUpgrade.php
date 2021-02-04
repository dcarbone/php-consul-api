<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class AutopilotUpgrade
 * @package DCarbone\PHPConsulAPI\Operator
 */
class AutopilotUpgrade extends AbstractModel
{
    private const FIELD_REDUNDANCY_ZONES             = 'RedundancyZones';

    /** @var string */
    public string $Status = '';
    /** @var string */
    public string $TargetVersion = '';
    /** @var string[] */
    public array $TargetVersionVoters = [];
    /** @var string[] */
    public array $TargetVersionNonVoters = [];
    /** @var string[] */
    public array $TargetVersionReadReplicas = [];
    /** @var string[] */
    public array $OtherVersionVoters = [];
    /** @var string[] */
    public array $OtherVersionNonVoters = [];
    /** @var string[] */
    public array $OtherVersionReadReplicas = [];
    /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotZoneUpgradeVersions[] */
    public array $RedundancyZones = [];

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_REDUNDANCY_ZONES             => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => AutopilotZoneUpgradeVersions::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
    ];

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @return string
     */
    public function getTargetVersion(): string
    {
        return $this->TargetVersion;
    }

    /**
     * @return string[]
     */
    public function getTargetVersionVoters(): array
    {
        return $this->TargetVersionVoters;
    }

    /**
     * @return string[]
     */
    public function getTargetVersionNonVoters(): array
    {
        return $this->TargetVersionNonVoters;
    }

    /**
     * @return string[]
     */
    public function getTargetVersionReadReplicas(): array
    {
        return $this->TargetVersionReadReplicas;
    }

    /**
     * @return string[]
     */
    public function getOtherVersionVoters(): array
    {
        return $this->OtherVersionVoters;
    }

    /**
     * @return string[]
     */
    public function getOtherVersionNonVoters(): array
    {
        return $this->OtherVersionNonVoters;
    }

    /**
     * @return string[]
     */
    public function getOtherVersionReadReplicas(): array
    {
        return $this->OtherVersionReadReplicas;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZoneUpgradeVersions[]
     */
    public function getRedundancyZones(): array
    {
        return $this->RedundancyZones;
    }
}