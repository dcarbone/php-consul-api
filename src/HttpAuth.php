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

/**
 * Class HttpAuth
 */
class HttpAuth implements \JsonSerializable
{
    private const FIELD_USERNAME = 'username';
    private const FIELD_PASSWORD = 'password';

    /** @var string */
    public string $username = '';
    /** @var string */
    public string $password = '';

    /**
     * ConsulHttpAuth constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username = '', string $password = '')
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function compileAuthString(): string
    {
        return (string)$this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [self::FIELD_USERNAME => $this->username, self::FIELD_PASSWORD => $this->password];
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [self::FIELD_USERNAME => $this->username];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return \trim(\sprintf('%s:%s', $this->username, $this->password), ':');
    }
}
