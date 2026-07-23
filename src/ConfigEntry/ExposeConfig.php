<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

class ExposeConfig extends AbstractType
{
    public bool $Checks;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\ExposePath> */
    public array $Paths;

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\ExposePath> $Paths
     */
    public function __construct(
        null|array $data = null,
        bool $Checks = false,
        array $Paths = [],
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Checks = $Checks;
        $this->setPaths(...$Paths);
    }

    public function isChecks(): bool
    {
        return $this->Checks;
    }

    public function setChecks(bool $Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ExposePath[]
     */
    public function getPaths(): array
    {
        return $this->Paths;
    }

    public function setPaths(ExposePath ...$Paths): self
    {
        $this->Paths = $Paths;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('Paths' === $k) {
                foreach ($v as $vv) {
                    $n->Paths[] = ExposePath::jsonUnserialize($vv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ($this->Checks) {
            $out->Checks = true;
        }
        if ([] !== $this->Paths) {
            $out->Paths = $this->Paths;
        }
        return $out;
    }
}
