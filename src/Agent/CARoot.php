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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

use function DCarbone\PHPConsulAPI\PHPLib\parse_time;

class CARoot extends AbstractType
{
    public string $ID;
    public string $Name;
    public string $RootCertPEM;
    public bool $Active;
    public null|Time\Time $NotBefore;
    public null|Time\Time $NotAfter;
    public null|int $DaysRemaining;
    public int $CreateIndex;
    public int $ModifyIndex;

    /**
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $ID = '',
        string $Name = '',
        string $RootCertPEM = '',
        bool $Active = false,
        null|Time\Time $NotBefore = null,
        null|Time\Time $NotAfter = null,
        null|int $DaysRemaining = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->ID = $ID;
        $this->Name = $Name;
        $this->RootCertPEM = $RootCertPEM;
        $this->Active = $Active;
        $this->NotBefore = $NotBefore;
        $this->NotAfter = $NotAfter;
        $this->DaysRemaining = $DaysRemaining;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getRootCertPEM(): string
    {
        return $this->RootCertPEM;
    }

    public function setRootCertPEM(string $RootCertPEM): self
    {
        $this->RootCertPEM = $RootCertPEM;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->Active;
    }

    public function setActive(bool $Active): self
    {
        $this->Active = $Active;
        return $this;
    }

    public function getNotBefore(): null|Time\Time
    {
        return $this->NotBefore;
    }

    public function setNotBefore(null|Time\Time $NotBefore): self
    {
        $this->NotBefore = $NotBefore;
        return $this;
    }

    public function getNotAfter(): null|Time\Time
    {
        return $this->NotAfter;
    }

    public function setNotAfter(null|Time\Time $NotAfter): self
    {
        $this->NotAfter = $NotAfter;
        return $this;
    }

    public function getDaysRemaining(): null|int
    {
        return $this->DaysRemaining;
    }

    public function setDaysRemaining(null|int $DaysRemaining): self
    {
        $this->DaysRemaining = $DaysRemaining;
        return $this;
    }

    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
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
            if ('NotBefore' === $k || 'NotAfter' === $k) {
                $n->{$k} = null === $v ? null : parse_time($v);
                continue;
            }
            if ('RootCert' === $k) {
                $n->RootCertPEM = $v;
                continue;
            }
            $n->{$k} = $v;
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ID = $this->ID;
        $out->Name = $this->Name;
        $out->RootCert = $this->RootCertPEM;
        $out->Active = $this->Active;
        if (null !== $this->NotBefore && !$this->NotBefore->isZero()) {
            $out->NotBefore = $this->NotBefore->format(DATE_RFC3339);
        }
        if (null !== $this->NotAfter && !$this->NotAfter->isZero()) {
            $out->NotAfter = $this->NotAfter->format(DATE_RFC3339);
        }
        if (null !== $this->DaysRemaining) {
            $out->DaysRemaining = $this->DaysRemaining;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
