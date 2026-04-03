<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Event;

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

class UserEvent extends AbstractType
{
    public string $ID;
    public string $Name;
    public string $Payload;
    public string $NodeFilter;
    public string $ServiceFilter;
    public string $TagFilter;
    public int $Version;
    public int $LTime;

    public function __construct(
        string $ID = '',
        string $Name = '',
        string $Payload = '',
        string $NodeFilter = '',
        string $ServiceFilter = '',
        string $TagFilter = '',
        int $Version = 0,
        int $LTime = 0,
    ) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Payload = $Payload;
        $this->NodeFilter = $NodeFilter;
        $this->ServiceFilter = $ServiceFilter;
        $this->TagFilter = $TagFilter;
        $this->Version = $Version;
        $this->LTime = $LTime;
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

    public function getPayload(): string
    {
        return $this->Payload;
    }

    public function setPayload(string $Payload): self
    {
        $this->Payload = $Payload;
        return $this;
    }

    public function getNodeFilter(): string
    {
        return $this->NodeFilter;
    }

    public function setNodeFilter(string $NodeFilter): self
    {
        $this->NodeFilter = $NodeFilter;
        return $this;
    }

    public function getServiceFilter(): string
    {
        return $this->ServiceFilter;
    }

    public function setServiceFilter(string $ServiceFilter): self
    {
        $this->ServiceFilter = $ServiceFilter;
        return $this;
    }

    public function getTagFilter(): string
    {
        return $this->TagFilter;
    }

    public function setTagFilter(string $TagFilter): self
    {
        $this->TagFilter = $TagFilter;
        return $this;
    }

    public function getVersion(): int
    {
        return $this->Version;
    }

    public function setVersion(int $Version): self
    {
        $this->Version = $Version;
        return $this;
    }

    public function getLTime(): int
    {
        return $this->LTime;
    }

    public function setLTime(int $LTime): self
    {
        $this->LTime = $LTime;
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
        $out->ID = $this->ID;
        $out->Name = $this->Name;
        $out->Payload = $this->Payload;
        $out->NodeFilter = $this->NodeFilter;
        $out->ServiceFilter = $this->ServiceFilter;
        $out->TagFilter = $this->TagFilter;
        $out->Version = $this->Version;
        $out->LTime = $this->LTime;
        return $out;
    }
}
