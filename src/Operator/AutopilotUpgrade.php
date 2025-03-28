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

class AutopilotUpgrade extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_TARGET_VERSION               => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_TARGET_VERSION_VOTERS        => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_TARGET_VERSION_NON_VOTERS    => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_TARGET_VERSION_READ_REPLICAS => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_OTHER_VERSION_VOTERS         => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_OTHER_VERSION_NON_VOTERS     => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_OTHER_VERSION_READ_REPLICAS  => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
        self::FIELD_REDUNDANCY_ZONES             => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => AutopilotZoneUpgradeVersions::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
    ];

    private const FIELD_TARGET_VERSION               = 'TargetVersion';
    private const FIELD_TARGET_VERSION_VOTERS        = 'TargetVersionVoters';
    private const FIELD_TARGET_VERSION_NON_VOTERS    = 'TargetVersionNonVoters';
    private const FIELD_TARGET_VERSION_READ_REPLICAS = 'TargetVersionReadReplicas';
    private const FIELD_OTHER_VERSION_VOTERS         = 'OtherVersionVoters';
    private const FIELD_OTHER_VERSION_NON_VOTERS     = 'OtherVersionNonVoters';
    private const FIELD_OTHER_VERSION_READ_REPLICAS  = 'OtherVersionReadReplicas';
    private const FIELD_REDUNDANCY_ZONES             = 'RedundancyZones';

    public string $Status = '';
    public string $TargetVersion = '';
    public array $TargetVersionVoters = [];
    public array $TargetVersionNonVoters = [];
    public array $TargetVersionReadReplicas = [];
    public array $OtherVersionVoters = [];
    public array $OtherVersionNonVoters = [];
    public array $OtherVersionReadReplicas = [];
    public array $RedundancyZones = [];

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;
        return $this;
    }

    public function getTargetVersion(): string
    {
        return $this->TargetVersion;
    }

    public function setTargetVersion(string $TargetVersion): self
    {
        $this->TargetVersion = $TargetVersion;
        return $this;
    }

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

    public function getTargetVersionReadReplicas(): array
    {
        return $this->TargetVersionReadReplicas;
    }

    public function setTargetVersionReadReplicas(array $TargetVersionReadReplicas): self
    {
        $this->TargetVersionReadReplicas = $TargetVersionReadReplicas;
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

    public function getOtherVersionReadReplicas(): array
    {
        return $this->OtherVersionReadReplicas;
    }

    public function setOtherVersionReadReplicas(array $OtherVersionReadReplicas): self
    {
        $this->OtherVersionReadReplicas = $OtherVersionReadReplicas;
        return $this;
    }

    public function getRedundancyZones(): array
    {
        return $this->RedundancyZones;
    }

    public function setRedundancyZones(array $RedundancyZones): self
    {
        $this->RedundancyZones = $RedundancyZones;
        return $this;
    }
}
