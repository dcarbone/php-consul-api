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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class AutopilotUpgrade extends AbstractType
{
    public string $Status;
    public string $TargetVersion;
    /** @var array<string> */
    public array $TargetVersionVoters;
    /** @var array<string> */
    public array $TargetVersionNonVoters;
    /** @var array<string> */
    public array $TargetVersionReadReplicas;
    /** @var array<string> */
    public array $OtherVersionVoters;
    /** @var array<string> */
    public array $OtherVersionNonVoters;
    /** @var array<string> */
    public array $OtherVersionReadReplicas;
    /** @var array<string, AutopilotZoneUpgradeVersions> */
    public array $RedundancyZones;

    /**
     * @param array<string> $TargetVersionVoters
     * @param array<string> $TargetVersionNonVoters
     * @param array<string> $TargetVersionReadReplicas
     * @param array<string> $OtherVersionVoters
     * @param array<string> $OtherVersionNonVoters
     * @param array<string> $OtherVersionReadReplicas
     * @param array<string, AutopilotZoneUpgradeVersions> $RedundancyZones
     */
    public function __construct(
        string $Status = '',
        string $TargetVersion = '',
        array $TargetVersionVoters = [],
        array $TargetVersionNonVoters = [],
        array $TargetVersionReadReplicas = [],
        array $OtherVersionVoters = [],
        array $OtherVersionNonVoters = [],
        array $OtherVersionReadReplicas = [],
        array $RedundancyZones = [],
    ) {
        $this->Status = $Status;
        $this->TargetVersion = $TargetVersion;
        $this->setTargetVersionVoters(...$TargetVersionVoters);
        $this->setTargetVersionNonVoters(...$TargetVersionNonVoters);
        $this->setTargetVersionReadReplicas(...$TargetVersionReadReplicas);
        $this->setOtherVersionVoters(...$OtherVersionVoters);
        $this->setOtherVersionNonVoters(...$OtherVersionNonVoters);
        $this->setOtherVersionReadReplicas(...$OtherVersionReadReplicas);
        $this->RedundancyZones = $RedundancyZones;
    }

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

    /**
     * @return array<string>
     */
    public function getTargetVersionVoters(): array
    {
        return $this->TargetVersionVoters;
    }

    public function setTargetVersionVoters(string ...$TargetVersionVoters): self
    {
        $this->TargetVersionVoters = $TargetVersionVoters;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getTargetVersionNonVoters(): array
    {
        return $this->TargetVersionNonVoters;
    }

    public function setTargetVersionNonVoters(string ...$TargetVersionNonVoters): self
    {
        $this->TargetVersionNonVoters = $TargetVersionNonVoters;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getTargetVersionReadReplicas(): array
    {
        return $this->TargetVersionReadReplicas;
    }

    public function setTargetVersionReadReplicas(string ...$TargetVersionReadReplicas): self
    {
        $this->TargetVersionReadReplicas = $TargetVersionReadReplicas;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getOtherVersionVoters(): array
    {
        return $this->OtherVersionVoters;
    }

    public function setOtherVersionVoters(string ...$OtherVersionVoters): self
    {
        $this->OtherVersionVoters = $OtherVersionVoters;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getOtherVersionNonVoters(): array
    {
        return $this->OtherVersionNonVoters;
    }

    public function setOtherVersionNonVoters(string ...$OtherVersionNonVoters): self
    {
        $this->OtherVersionNonVoters = $OtherVersionNonVoters;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getOtherVersionReadReplicas(): array
    {
        return $this->OtherVersionReadReplicas;
    }

    public function setOtherVersionReadReplicas(string ...$OtherVersionReadReplicas): self
    {
        $this->OtherVersionReadReplicas = $OtherVersionReadReplicas;
        return $this;
    }

    /**
     * @return array<string, AutopilotZoneUpgradeVersions>
     */
    public function getRedundancyZones(): array
    {
        return $this->RedundancyZones;
    }

    /**
     * @param array<string, AutopilotZoneUpgradeVersions> $RedundancyZones
     */
    public function setRedundancyZones(array $RedundancyZones): self
    {
        $this->RedundancyZones = $RedundancyZones;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('RedundancyZones' === $k) {
                $n->RedundancyZones = [];
                foreach ($v as $zk => $zv) {
                    $n->RedundancyZones[$zk] = AutopilotZoneUpgradeVersions::jsonUnserialize($zv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Status = $this->Status;
        if ('' !== $this->TargetVersion) {
            $out->TargetVersion = $this->TargetVersion;
        }
        if ([] !== $this->TargetVersionVoters) {
            $out->TargetVersionVoters = $this->TargetVersionVoters;
        }
        if ([] !== $this->TargetVersionNonVoters) {
            $out->TargetVersionNonVoters = $this->TargetVersionNonVoters;
        }
        if ([] !== $this->TargetVersionReadReplicas) {
            $out->TargetVersionReadReplicas = $this->TargetVersionReadReplicas;
        }
        if ([] !== $this->OtherVersionVoters) {
            $out->OtherVersionVoters = $this->OtherVersionVoters;
        }
        if ([] !== $this->OtherVersionNonVoters) {
            $out->OtherVersionNonVoters = $this->OtherVersionNonVoters;
        }
        if ([] !== $this->OtherVersionReadReplicas) {
            $out->OtherVersionReadReplicas = $this->OtherVersionReadReplicas;
        }
        if ([] !== $this->RedundancyZones) {
            $out->RedundancyZones = $this->RedundancyZones;
        }
        return $out;
    }
}
