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

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

use function DCarbone\PHPConsulAPI\PHPLib\uint64;

class RingHashConfig extends AbstractType
{
    public BigInteger $MinimumRingSize;
    public BigInteger $MaximumRingSize;

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        int|string|BigInteger $MinimumRingSize = 0,
        int|string|BigInteger $MaximumRingSize = 0
    ) {
        $this->MinimumRingSize = uint64($MinimumRingSize);
        $this->MaximumRingSize = uint64($MaximumRingSize);
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
        }
    }

    public function getMinimumRingSize(): BigInteger
    {
        return $this->MinimumRingSize;
    }

    public function setMinimumRingSize(int|string|BigInteger $MinimumRingSize): self
    {
        $this->MinimumRingSize = uint64($MinimumRingSize);
        return $this;
    }

    public function getMaximumRingSize(): BigInteger
    {
        return $this->MaximumRingSize;
    }

    public function setMaximumRingSize(int|string|BigInteger $MaximumRingSize): self
    {
        $this->MaximumRingSize = uint64($MaximumRingSize);
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
            if ('MinimumRingSize' === $k || 'minimum_ring_size' === $k) {
                $n->MinimumRingSize = uint64($v);
            } elseif ('MaximumRingSize' === $k || 'maximum_ring_size' === $k) {
                $n->MaximumRingSize = uint64($v);
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (!$this->MinimumRingSize->isZero()) {
            $out->MinimumRingSize = $this->MinimumRingSize;
        }
        if (!$this->MaximumRingSize->isZero()) {
            $out->MaximumRingSize = $this->MaximumRingSize;
        }
        return $out;
    }
}
