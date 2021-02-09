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
            Hydration::FIELD_CALLBACK => Hydration::CALLABLE_HYDRATE_DURATION,
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
    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $RTT = null;

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @return string
     */
    public function getAddr(): string
    {
        return $this->Addr;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->Port;
    }

    /**
     * @return string
     */
    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->Role;
    }

    /**
     * @return string
     */
    public function getBuild(): string
    {
        return $this->Build;
    }

    /**
     * @return int
     */
    public function getProtocol(): int
    {
        return $this->Protocol;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @return \DCarbone\Go\Time\Duration|null
     */
    public function getRTT(): ?Time\Duration
    {
        return $this->RTT;
    }
}
