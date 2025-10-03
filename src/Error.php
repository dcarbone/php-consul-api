<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

class Error implements \JsonSerializable
{
    private Time\Time $time;

    private string $message;

    public function __construct(string $message)
    {
        $this->time = Time::Now();
        $this->message = $message;
    }

    public static function unexpectedResponseCodeError(RequestResponse $resp): self
    {
        return new self(
            sprintf(
                'unexpected response code: %d (%s)',
                $resp->Response->getStatusCode(),
                $resp->Response->getBody()->getContents(),
            )
        );
    }

    public function getTime(): Time\Time
    {
        return $this->time;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        $out->message = $this->message;
        $out->timestamp = $this->time;
        return $out;
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
