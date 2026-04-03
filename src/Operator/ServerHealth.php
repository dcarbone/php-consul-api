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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class ServerHealth extends AbstractType
{
    public string $ID;
    public string $Name;
    public string $Address;
    public string $SerfStatus;
    public string $Version;
    public bool $Leader;
    public null|Time\Duration $LastContact;
    public int $LastTerm;
    public int $LastIndex;
    public bool $Healthy;
    public bool $Voter;
    public Time\Time $StableSince;

    public function __construct(
        string $ID = '',
        string $Name = '',
        string $Address = '',
        string $SerfStatus = '',
        string $Version = '',
        bool $Leader = false,
        null|string|int|float|\DateInterval|Time\Duration $LastContact = null,
        int $LastTerm = 0,
        int $LastIndex = 0,
        bool $Healthy = false,
        bool $Voter = false,
        null|Time\Time $StableSince = null,
    ) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Address = $Address;
        $this->SerfStatus = $SerfStatus;
        $this->Version = $Version;
        $this->Leader = $Leader;
        $this->setLastContact($LastContact);
        $this->LastTerm = $LastTerm;
        $this->LastIndex = $LastIndex;
        $this->Healthy = $Healthy;
        $this->Voter = $Voter;
        $this->StableSince = $StableSince ?? Time::New();
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

    public function getAddress(): string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;
        return $this;
    }

    public function getSerfStatus(): string
    {
        return $this->SerfStatus;
    }

    public function setSerfStatus(string $SerfStatus): self
    {
        $this->SerfStatus = $SerfStatus;
        return $this;
    }

    public function getVersion(): string
    {
        return $this->Version;
    }

    public function setVersion(string $Version): self
    {
        $this->Version = $Version;
        return $this;
    }

    public function isLeader(): bool
    {
        return $this->Leader;
    }

    public function setLeader(bool $Leader): self
    {
        $this->Leader = $Leader;
        return $this;
    }

    public function getLastContact(): null|Time\Duration
    {
        return $this->LastContact;
    }

    public function setLastContact(null|string|int|float|\DateInterval|Time\Duration $LastContact): self
    {
        if (null === $LastContact) {
            $this->LastContact = null;
            return $this;
        }
        $this->LastContact = Time::Duration($LastContact);
        return $this;
    }

    public function getLastTerm(): int
    {
        return $this->LastTerm;
    }

    public function setLastTerm(int $LastTerm): self
    {
        $this->LastTerm = $LastTerm;
        return $this;
    }

    public function getLastIndex(): int
    {
        return $this->LastIndex;
    }

    public function setLastIndex(int $LastIndex): self
    {
        $this->LastIndex = $LastIndex;
        return $this;
    }

    public function isHealthy(): bool
    {
        return $this->Healthy;
    }

    public function setHealthy(bool $Healthy): self
    {
        $this->Healthy = $Healthy;
        return $this;
    }

    public function isVoter(): bool
    {
        return $this->Voter;
    }

    public function setVoter(bool $Voter): self
    {
        $this->Voter = $Voter;
        return $this;
    }

    public function getStableSince(): Time\Time
    {
        return $this->StableSince;
    }

    public function setStableSince(Time\Time $StableSince): self
    {
        $this->StableSince = $StableSince;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('LastContact' === $k) {
                $n->setLastContact($v);
            } elseif ('StableSince' === $k) {
                $n->StableSince = Time\Time::createFromFormat(DATE_RFC3339, $v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ID = $this->ID;
        $out->Name = $this->Name;
        $out->Address = $this->Address;
        $out->SerfStatus = $this->SerfStatus;
        $out->Version = $this->Version;
        $out->Leader = $this->Leader;
        if (null !== $this->LastContact) {
            $out->LastContact = (string)$this->LastContact;
        }
        $out->LastTerm = $this->LastTerm;
        $out->LastIndex = $this->LastIndex;
        $out->Healthy = $this->Healthy;
        $out->Voter = $this->Voter;
        $out->StableSince = $this->StableSince;
        return $out;
    }
}
