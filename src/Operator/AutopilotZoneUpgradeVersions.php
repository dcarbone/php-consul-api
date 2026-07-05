<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

class AutopilotZoneUpgradeVersions extends AbstractType
{
    /** @var array<string> */
    public array $TargetVersionVoters;
    /** @var array<string> */
    public array $TargetVersionNonVoters;
    /** @var array<string> */
    public array $OtherVersionVoters;
    /** @var array<string> */
    public array $OtherVersionNonVoters;

    /**
     * @param array<string> $TargetVersionVoters
     * @param array<string> $TargetVersionNonVoters
     * @param array<string> $OtherVersionVoters
     * @param array<string> $OtherVersionNonVoters
     */
    public function __construct(
        array $TargetVersionVoters = [],
        array $TargetVersionNonVoters = [],
        array $OtherVersionVoters = [],
        array $OtherVersionNonVoters = [],
    ) {
        $this->setTargetVersionVoters(...$TargetVersionVoters);
        $this->setTargetVersionNonVoters(...$TargetVersionNonVoters);
        $this->setOtherVersionVoters(...$OtherVersionVoters);
        $this->setOtherVersionNonVoters(...$OtherVersionNonVoters);
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ([] !== $this->TargetVersionVoters) {
            $out->TargetVersionVoters = $this->TargetVersionVoters;
        }
        if ([] !== $this->TargetVersionNonVoters) {
            $out->TargetVersionNonVoters = $this->TargetVersionNonVoters;
        }
        if ([] !== $this->OtherVersionVoters) {
            $out->OtherVersionVoters = $this->OtherVersionVoters;
        }
        if ([] !== $this->OtherVersionNonVoters) {
            $out->OtherVersionNonVoters = $this->OtherVersionNonVoters;
        }
        return $out;
    }
}
