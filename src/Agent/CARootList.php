<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

class CARootList extends AbstractType
{
    public string $ActiveRootID;
    public string $TrustDomain;
    /** @var CARoot[] */
    public array $Roots;

    /**
     * @param CARoot[] $Roots
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $ActiveRootID = '',
        string $TrustDomain = '',
        array $Roots = [],
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->ActiveRootID = $ActiveRootID;
        $this->TrustDomain = $TrustDomain;
        $this->Roots = $Roots;
    }

    public function getActiveRootID(): string
    {
        return $this->ActiveRootID;
    }

    public function setActiveRootID(string $ActiveRootID): self
    {
        $this->ActiveRootID = $ActiveRootID;
        return $this;
    }

    public function getTrustDomain(): string
    {
        return $this->TrustDomain;
    }

    public function setTrustDomain(string $TrustDomain): self
    {
        $this->TrustDomain = $TrustDomain;
        return $this;
    }

    /**
     * @return CARoot[]
     */
    public function getRoots(): array
    {
        return $this->Roots;
    }

    /**
     * @param CARoot ...$Roots
     */
    public function setRoots(CARoot ...$Roots): self
    {
        $this->Roots = $Roots;
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
            if ('Roots' === $k) {
                $n->Roots = [];
                foreach ($v as $root) {
                    $n->Roots[] = CARoot::jsonUnserialize($root);
                }
                continue;
            }
            $n->{$k} = $v;
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ActiveRootID = $this->ActiveRootID;
        $out->TrustDomain = $this->TrustDomain;
        if ([] !== $this->Roots) {
            $out->Roots = $this->Roots;
        }
        return $out;
    }
}
