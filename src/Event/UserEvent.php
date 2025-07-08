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

use DCarbone\PHPConsulAPI\AbstractModel;

class UserEvent extends AbstractModel
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
        int $LTime = 0
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

    public function getName(): string
    {
        return $this->Name;
    }

    public function getPayload(): string
    {
        return $this->Payload;
    }

    public function getNodeFilter(): string
    {
        return $this->NodeFilter;
    }

    public function getServiceFilter(): string
    {
        return $this->ServiceFilter;
    }

    public function getTagFilter(): string
    {
        return $this->TagFilter;
    }

    public function getVersion(): int
    {
        return $this->Version;
    }

    public function getLTime(): int
    {
        return $this->LTime;
    }

    public static function jsonUnserialize(\stdclass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
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
