<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

/**
 * TODO: Make this better...
 *
 * Class Error
 */
class Error implements \JsonSerializable
{
    private const FIELD_MESSAGE   = 'message';
    private const FIELD_TIMESTAMP = 'timestamp';

    /** @var \DCarbone\Go\Time\Time */
    private Time\Time $time;

    /** @var string */
    private string $message;

    /**
     * Error constructor.
     * @param string $message
     * @throws \Exception
     */
    public function __construct(string $message)
    {
        $this->time    = Time::Now();
        $this->message = $message;
    }

    /**
     * @return \DCarbone\Go\Time\Time
     */
    public function getTime(): Time\Time
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            self::FIELD_MESSAGE   => $this->message,
            self::FIELD_TIMESTAMP => $this->time,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->message;
    }
}
