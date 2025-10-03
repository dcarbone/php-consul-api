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

class UpstreamConfiguration extends AbstractType
{
    /** @var array<null|\DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfig> */
    public array $Overrides;
    public null|UpstreamConfig $Defaults;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfig> $Overrides
     */
    public function __construct(
        array $Overrides = [],
        null|UpstreamConfig $Defaults = null
    ) {
        $this->setOverrides(...$Overrides);
        $this->Defaults = $Defaults;
    }

    /**
     * @return array<null|\DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfig>
     */
    public function getOverrides(): array
    {
        return $this->Overrides;
    }

    public function setOverrides(null|UpstreamConfig ...$Overrides): self
    {
        $this->Overrides = $Overrides;
        return $this;
    }

    public function getDefaults(): null|UpstreamConfig
    {
        return $this->Defaults;
    }

    public function setDefaults(null|UpstreamConfig $Defaults): self
    {
        $this->Defaults = $Defaults;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Overrides' === $k) {
                $n->Overrides = [];
                foreach ($v as $vv) {
                    $n->Overrides[] = UpstreamConfig::jsonUnserialize($vv);
                }
            } elseif ('Defaults' === $k) {
                $n->Defaults = null === $v ? null : UpstreamConfig::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ([] !== $this->Overrides) {
            $out->Overrides = $this->Overrides;
        }
        if (null !== $this->Defaults) {
            $out->Defaults = $this->Defaults;
        }
        return $out;
    }
}
