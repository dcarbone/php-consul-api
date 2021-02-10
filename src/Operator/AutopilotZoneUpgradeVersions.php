<?php declare(strict_types=1);

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AutopilotZoneUpgradeVersions
 */
class AutopilotZoneUpgradeVersions extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_TARGET_VERSION_VOTERS     => Hydration::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_TARGET_VERSION_NON_VOTERS => Hydration::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_OTHER_VERSION_VOTERS      => Hydration::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_OTHER_VERSION_NON_VOTERS  => Hydration::OMITEMPTY_STRING_ARRAY_FIELD,
    ];

    private const FIELD_TARGET_VERSION_VOTERS     = 'TargetVersionVoters';
    private const FIELD_TARGET_VERSION_NON_VOTERS = 'TargetVersionNonVoters';
    private const FIELD_OTHER_VERSION_VOTERS      = 'OtherVersionVoters';
    private const FIELD_OTHER_VERSION_NON_VOTERS  = 'OtherVersionNonVoters';

    /** @var string[] */
    public array $TargetVersionVoters = [];
    /** @var string[] */
    public array $TargetVersionNonVoters = [];
    /** @var string[] */
    public array $OtherVersionVoters = [];
    /** @var string[] */
    public array $OtherVersionNonVoters = [];

    /**
     * @return string[]
     */
    public function getTargetVersionVoters(): array
    {
        return $this->TargetVersionVoters;
    }

    /**
     * @param string[] $TargetVersionVoters
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZoneUpgradeVersions
     */
    public function setTargetVersionVoters(array $TargetVersionVoters): self
    {
        $this->TargetVersionVoters = $TargetVersionVoters;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTargetVersionNonVoters(): array
    {
        return $this->TargetVersionNonVoters;
    }

    /**
     * @param string[] $TargetVersionNonVoters
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZoneUpgradeVersions
     */
    public function setTargetVersionNonVoters(array $TargetVersionNonVoters): self
    {
        $this->TargetVersionNonVoters = $TargetVersionNonVoters;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getOtherVersionVoters(): array
    {
        return $this->OtherVersionVoters;
    }

    /**
     * @param string[] $OtherVersionVoters
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZoneUpgradeVersions
     */
    public function setOtherVersionVoters(array $OtherVersionVoters): self
    {
        $this->OtherVersionVoters = $OtherVersionVoters;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getOtherVersionNonVoters(): array
    {
        return $this->OtherVersionNonVoters;
    }

    /**
     * @param string[] $OtherVersionNonVoters
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZoneUpgradeVersions
     */
    public function setOtherVersionNonVoters(array $OtherVersionNonVoters): self
    {
        $this->OtherVersionNonVoters = $OtherVersionNonVoters;
        return $this;
    }
}
