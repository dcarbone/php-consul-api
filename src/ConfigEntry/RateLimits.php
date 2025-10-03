<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class RateLimits extends AbstractType
{
    public InstanceLevelRateLimits $InstanceLevel;

    public function __construct(null|InstanceLevelRateLimits $instanceLevel = null)
    {
        $this->InstanceLevel = $instanceLevel ?? new InstanceLevelRateLimits();
    }

    public function getInstanceLevel(): InstanceLevelRateLimits
    {
        return $this->InstanceLevel;
    }

    public function setInstanceLevel(InstanceLevelRateLimits $instanceLevel): self
    {
        $this->InstanceLevel = $instanceLevel;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('InstanceLevel' === $k || 'instance_level' === $k) {
                $n->InstanceLevel = InstanceLevelRateLimits::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->InstanceLevel = $this->InstanceLevel;
        return $out;
    }
}
