<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Transcoding;

class AutopilotZoneUpgradeVersions extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_TARGET_VERSION_VOTERS     => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_TARGET_VERSION_NON_VOTERS => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_OTHER_VERSION_VOTERS      => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_OTHER_VERSION_NON_VOTERS  => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
    ];

    private const FIELD_TARGET_VERSION_VOTERS     = 'TargetVersionVoters';
    private const FIELD_TARGET_VERSION_NON_VOTERS = 'TargetVersionNonVoters';
    private const FIELD_OTHER_VERSION_VOTERS      = 'OtherVersionVoters';
    private const FIELD_OTHER_VERSION_NON_VOTERS  = 'OtherVersionNonVoters';

    public array $TargetVersionVoters;
    public array $TargetVersionNonVoters;
    public array $OtherVersionVoters;
    public array $OtherVersionNonVoters;

    public function getTargetVersionVoters(): array
    {
        return $this->TargetVersionVoters;
    }

    public function setTargetVersionVoters(array $TargetVersionVoters): self
    {
        $this->TargetVersionVoters = $TargetVersionVoters;
        return $this;
    }

    public function getTargetVersionNonVoters(): array
    {
        return $this->TargetVersionNonVoters;
    }

    public function setTargetVersionNonVoters(array $TargetVersionNonVoters): self
    {
        $this->TargetVersionNonVoters = $TargetVersionNonVoters;
        return $this;
    }

    public function getOtherVersionVoters(): array
    {
        return $this->OtherVersionVoters;
    }

    public function setOtherVersionVoters(array $OtherVersionVoters): self
    {
        $this->OtherVersionVoters = $OtherVersionVoters;
        return $this;
    }

    public function getOtherVersionNonVoters(): array
    {
        return $this->OtherVersionNonVoters;
    }

    public function setOtherVersionNonVoters(array $OtherVersionNonVoters): self
    {
        $this->OtherVersionNonVoters = $OtherVersionNonVoters;
        return $this;
    }
}
