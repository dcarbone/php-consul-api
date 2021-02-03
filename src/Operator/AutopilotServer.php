<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AutopilotServer
 * @package DCarbone\PHPConsulAPI\Operator
 */
class AutopilotServer extends AbstractModel implements \JsonSerializable
{
    private const FIELD_LAST_CONTACT = 'LastContact';
    private const FIELD_STABLE_SINCE = 'StableSince';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Address = '';
    /** @var string */
    public string $NodeStatus = '';
    /** @var string */
    public string $Version = '';
    /** @var \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null */
    public ?ReadableDuration $LastContact = null;
    /** @var int */
    public int $LastTerm = 0;
    /** @var int */
    public int $LastIndex = 0;
    /** @var \DCarbone\Go\Time\Time|null */
    public ?Time\Time $StableSince = null;
    /** @var string */
    public string $RedundancyZone = '';
    /** @var string */
    public string $UpgradeVersion = '';
    /** @var bool */
    public bool $ReadReplica = false;
    /** @var string */
    public string $Status = '';
    /** @var array */
    public array $Meta = [];
    /** @var string */
    public string $NodeType = '';

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_LAST_CONTACT => [
            Hydration::FIELD_HYDRATE_CALLABLE => [ReadableDuration::class, 'hydrate'],
        ],
        self::FIELD_STABLE_SINCE => [
            Hydration::FIELD_HYDRATE_CALLABLE => Hydration::HYDRATE_TIME_CALLABLE,
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
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->Address;
    }

    /**
     * @return string
     */
    public function getNodeStatus(): string
    {
        return $this->NodeStatus;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->Version;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ReadableDuration|null
     */
    public function getLastContact(): ?ReadableDuration
    {
        return $this->LastContact;
    }

    /**
     * @return int
     */
    public function getLastTerm(): int
    {
        return $this->LastTerm;
    }

    /**
     * @return int
     */
    public function getLastIndex(): int
    {
        return $this->LastIndex;
    }

    /**
     * @return \DCarbone\Go\Time\Time|null
     */
    public function getStableSince(): ?Time\Time
    {
        return $this->StableSince;
    }

    /**
     * @return string
     */
    public function getRedundancyZone(): string
    {
        return $this->RedundancyZone;
    }

    /**
     * @return string
     */
    public function getUpgradeVersion(): string
    {
        return $this->UpgradeVersion;
    }

    /**
     * @return bool
     */
    public function isReadReplica(): bool
    {
        return $this->ReadReplica;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @return array|null
     */
    public function getMeta(): ?array
    {
        return $this->Meta;
    }

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return $this->NodeType;
    }

    /**
     * @return array|mixed|void
     */
    public function jsonSerialize(): array
    {
        $arr = parent::jsonSerialize();
        if (isset($this->StableSince)) {
            $arr[self::FIELD_STABLE_SINCE] = $this->StableSince->format(Time\Time::DefaultFormat);
        }
        return $arr;
    }
}