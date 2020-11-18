<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\HasStringTags;

/**
 * Class AgentMember
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentMember extends AbstractModel
{
    use HasStringTags;

    /** @var string */
    public $Name = '';
    /** @var string */
    public $Addr = '';
    /** @var int */
    public $Port = 0;
    /** @var string */
    public $Status = '';
    /** @var int */
    public $ProtocolMin = 0;
    /** @var int */
    public $ProtocolMax = 0;
    /** @var int */
    public $ProtocolCur = 0;
    /** @var int */
    public $DelegateMin = 0;
    /** @var int */
    public $DelegateMax = 0;
    /** @var int */
    public $DelegateCur = 0;

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
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @return int
     */
    public function getProtocolMin(): int
    {
        return $this->ProtocolMin;
    }

    /**
     * @return int
     */
    public function getProtocolMax(): int
    {
        return $this->ProtocolMax;
    }

    /**
     * @return int
     */
    public function getProtocolCur(): int
    {
        return $this->ProtocolCur;
    }

    /**
     * @return int
     */
    public function getDelegateMin(): int
    {
        return $this->DelegateMin;
    }

    /**
     * @return int
     */
    public function getDelegateMax(): int
    {
        return $this->DelegateMax;
    }

    /**
     * @return int
     */
    public function getDelegateCur(): int
    {
        return $this->DelegateCur;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->Name;
    }
}