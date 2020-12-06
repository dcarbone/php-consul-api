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

/**
 * Class AutopilotZoneUpgradeVersions
 * @package DCarbone\PHPConsulAPI\Operator
 */
class AutopilotZoneUpgradeVersions extends AbstractModel
{
    /** @var string[]|null */
    public $TargetVersionVoters = null;
    /** @var string[]|null */
    public $TargetVersionNonVoters = null;
    /** @var string[]|null */
    public $OtherVersionVoters = null;
    /** @var string[]|null */
    public $OtherVersionNonVoters = null;

    /**
     * @return string[]|null
     */
    public function getTargetVersionVoters(): ?array
    {
        return $this->TargetVersionVoters;
    }

    /**
     * @return string[]|null
     */
    public function getTargetVersionNonVoters(): ?array
    {
        return $this->TargetVersionNonVoters;
    }

    /**
     * @return string[]|null
     */
    public function getOtherVersionVoters(): ?array
    {
        return $this->OtherVersionVoters;
    }

    /**
     * @return string[]|null
     */
    public function getOtherVersionNonVoters(): ?array
    {
        return $this->OtherVersionNonVoters;
    }
}