<?php

declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class AutopilotUpgrade
 */
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

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade
     */
    public function setStatus(string $Status): self
    {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetVersion(): string
    {
        return $this->TargetVersion;
    }

    /**
     * @param string $TargetVersion
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade
     */
    public function setTargetVersion(string $TargetVersion): self
    {
        $this->TargetVersion = $TargetVersion;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTargetVersionVoters(): array
    {
        return $this->TargetVersionVoters;
    }

    /**
     * @param string[] $TargetVersionVoters
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade
     */
    public function setTargetVersionNonVoters(array $TargetVersionNonVoters): self
    {
        $this->TargetVersionNonVoters = $TargetVersionNonVoters;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTargetVersionReadReplicas(): array
    {
        return $this->TargetVersionReadReplicas;
    }

    /**
     * @param string[] $TargetVersionReadReplicas
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade
     */
    public function setTargetVersionReadReplicas(array $TargetVersionReadReplicas): self
    {
        $this->TargetVersionReadReplicas = $TargetVersionReadReplicas;
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade
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
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade
     */
    public function setOtherVersionNonVoters(array $OtherVersionNonVoters): self
    {
        $this->OtherVersionNonVoters = $OtherVersionNonVoters;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getOtherVersionReadReplicas(): array
    {
        return $this->OtherVersionReadReplicas;
    }

    /**
     * @param string[] $OtherVersionReadReplicas
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade
     */
    public function setOtherVersionReadReplicas(array $OtherVersionReadReplicas): self
    {
        $this->OtherVersionReadReplicas = $OtherVersionReadReplicas;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotZoneUpgradeVersions[]
     */
    public function getRedundancyZones(): array
    {
        return $this->RedundancyZones;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\AutopilotZoneUpgradeVersions[] $RedundancyZones
     * @return \DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade
     */
    public function setRedundancyZones(array $RedundancyZones): self
    {
        $this->RedundancyZones = $RedundancyZones;
        return $this;
    }
}
