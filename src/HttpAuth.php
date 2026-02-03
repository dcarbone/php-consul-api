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

class HttpAuth implements \JsonSerializable
{
    public string $username;
    public string $password;

    public function __construct(string $username = '', string $password = '')
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function compileAuthString(): string
    {
        return (string)$this;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        $out->username = $this->username;
        if ('' !== $this->password) {
            $out->password = $this->password;
        }
        return $out;
    }

    public function __debugInfo(): array
    {
        return ['username' => $this->username];
    }

    public function __toString(): string
    {
        return $this->password !== '' ? "{$this->username}:{$this->password}" : $this->username;
    }
}
