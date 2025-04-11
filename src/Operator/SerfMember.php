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
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

class SerfMember extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_RTT => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_DURATION,
        ],
    ];

    private const FIELD_RTT = 'RTT';

    public string $ID = '';
    public string $Name = '';
    public string $Addr = '';
    public int $Port = 0;
    public string $Datacenter = '';
    public string $Role = '';
    public string $Build = '';
    public int $Protocol = 0;
    public string $Status = '';
    public Time\Duration $RTT;

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->RTT)) {
            $this->RTT = new Time\Duration(0);
        }
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

    public function getAddr(): string
    {
        return $this->Addr;
    }

    public function setAddr(string $Addr): self
    {
        $this->Addr = $Addr;
        return $this;
    }

    public function getPort(): int
    {
        return $this->Port;
    }

    public function setPort(int $Port): self
    {
        $this->Port = $Port;
        return $this;
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    public function getRole(): string
    {
        return $this->Role;
    }

    public function setRole(string $Role): self
    {
        $this->Role = $Role;
        return $this;
    }

    public function getBuild(): string
    {
        return $this->Build;
    }

    public function setBuild(string $Build): self
    {
        $this->Build = $Build;
        return $this;
    }

    public function getProtocol(): int
    {
        return $this->Protocol;
    }

    public function setProtocol(int $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;
        return $this;
    }

    public function getRTT(): Time\Duration
    {
        return $this->RTT;
    }

    public function setRTT(Time\Duration $RTT): self
    {
        $this->RTT = $RTT;
        return $this;
    }
}
