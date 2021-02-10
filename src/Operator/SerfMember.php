<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class SerfMember
 */
class SerfMember extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_RTT => [
            Hydration::FIELD_CALLBACK => Hydration::HYDRATE_DURATION,
        ],
    ];

    private const FIELD_RTT = 'RTT';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Addr = '';
    /** @var int */
    public int $Port = 0;
    /** @var string */
    public string $Datacenter = '';
    /** @var string */
    public string $Role = '';
    /** @var string */
    public string $Build = '';
    /** @var int */
    public int $Protocol = 0;
    /** @var string */
    public string $Status = '';
    /** @var \DCarbone\Go\Time\Duration */
    public Time\Duration $RTT;

    /**
     * SerfMember constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->RTT)) {
            $this->RTT = new Time\Duration(0);
        }
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddr(): string
    {
        return $this->Addr;
    }

    /**
     * @param string $Addr
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setAddr(string $Addr): self
    {
        $this->Addr = $Addr;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->Port;
    }

    /**
     * @param int $Port
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setPort(int $Port): self
    {
        $this->Port = $Port;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    /**
     * @param string $Datacenter
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setDatacenter(string $Datacenter): self
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->Role;
    }

    /**
     * @param string $Role
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setRole(string $Role): self
    {
        $this->Role = $Role;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuild(): string
    {
        return $this->Build;
    }

    /**
     * @param string $Build
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setBuild(string $Build): self
    {
        $this->Build = $Build;
        return $this;
    }

    /**
     * @return int
     */
    public function getProtocol(): int
    {
        return $this->Protocol;
    }

    /**
     * @param int $Protocol
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setProtocol(int $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setStatus(string $Status): self
    {
        $this->Status = $Status;
        return $this;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getRTT(): Time\Duration
    {
        return $this->RTT;
    }

    /**
     * @param \DCarbone\Go\Time\Duration $RTT
     * @return \DCarbone\PHPConsulAPI\Operator\SerfMember
     */
    public function setRTT(Time\Duration $RTT): self
    {
        $this->RTT = $RTT;
        return $this;
    }
}
