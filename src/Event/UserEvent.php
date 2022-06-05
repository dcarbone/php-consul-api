<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Event;

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

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class UserEvent
 */
class UserEvent extends AbstractModel
{
    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Payload = '';
    /** @var string */
    public string $NodeFilter = '';
    /** @var string */
    public string $ServiceFilter = '';
    /** @var string */
    public string $TagFilter = '';
    /** @var int */
    public int $Version = 0;
    /** @var int */
    public int $LTime = 0;

    /**
     * UserEvent constructor.
     *
     * @param array $data
     * @param bool $_decodeValue
     */
    public function __construct(array $data = [], bool $_decodeValue = false)
    {
        parent::__construct($data);
        if ($_decodeValue) {
            $dec = base64_decode($this->Payload, true);
            if (false === $dec) {
                throw new \InvalidArgumentException(sprintf('Could not base64 decode payload "%s"', $this->Payload));
            }
            $this->Payload = $dec;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->Payload;
    }

    /**
     * @return string
     */
    public function getNodeFilter(): string
    {
        return $this->NodeFilter;
    }

    /**
     * @return string
     */
    public function getServiceFilter(): string
    {
        return $this->ServiceFilter;
    }

    /**
     * @return string
     */
    public function getTagFilter(): string
    {
        return $this->TagFilter;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->Version;
    }

    /**
     * @return int
     */
    public function getLTime(): int
    {
        return $this->LTime;
    }
}
