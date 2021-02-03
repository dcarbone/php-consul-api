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
use DCarbone\PHPConsulAPI\HasSettableStringTags;
use DCarbone\PHPConsulAPI\HasStringTags;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AgentServiceRegistration
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentServiceRegistration extends AbstractModel
{
    private const FIELD_META   = 'Meta';
    private const FIELD_CHECK  = 'Check';
    private const FIELD_CHECKS = 'Checks';

    use HasSettableStringTags;
    use HasStringTags;

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var int */
    public int $Port = 0;
    /** @var string */
    public string $Address = '';
    /** @var bool */
    public bool $EnableTagOverride = false;
    /** @var array */
    public array $Meta = [];
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck|null */
    public ?AgentServiceCheck $Check = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck[] */
    public array $Checks = [];

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_META   => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_ARRAY_TYPE => Hydration::MIXED,
        ],
        self::FIELD_CHECK  => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => AgentServiceCheck::class,
        ],
        self::FIELD_CHECKS => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => AgentServiceCheck::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
    ];

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return AgentServiceRegistration
     */
    public function setID(string $ID): AgentServiceRegistration
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
     * @return AgentServiceRegistration
     */
    public function setName(string $Name): AgentServiceRegistration
    {
        $this->Name = $Name;
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
     * @return AgentServiceRegistration
     */
    public function setPort(int $Port): AgentServiceRegistration
    {
        $this->Port = $Port;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return AgentServiceRegistration
     */
    public function setAddress(string $Address): AgentServiceRegistration
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableTagOverride(): bool
    {
        return $this->EnableTagOverride;
    }

    /**
     * @param bool $EnableTagOverride
     * @return AgentServiceRegistration
     */
    public function setEnableTagOverride(bool $EnableTagOverride): AgentServiceRegistration
    {
        $this->EnableTagOverride = $EnableTagOverride;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->Meta;
    }

    /**
     * @param array $Meta
     * @return AgentServiceRegistration
     */
    public function setMeta(array $Meta): AgentServiceRegistration
    {
        $this->Meta = $Meta;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck|null
     */
    public function getCheck(): ?AgentServiceCheck
    {
        return $this->Check;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck|null $Check
     * @return AgentServiceRegistration
     */
    public function setCheck(?AgentServiceCheck $Check): AgentServiceRegistration
    {
        $this->Check = $Check;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck[]
     */
    public function getChecks(): array
    {
        return $this->Checks;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck[] $Checks
     * @return AgentServiceRegistration
     */
    public function setChecks(array $Checks): AgentServiceRegistration
    {
        $this->Checks = $Checks;
        return $this;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck $check
     * @return AgentServiceRegistration
     */
    public function addCheck(AgentServiceCheck $check): AgentServiceRegistration
    {
        $this->Checks[] = $check;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->Name;
    }
}