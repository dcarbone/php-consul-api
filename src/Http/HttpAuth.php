<?php namespace DCarbone\PHPConsulAPI\Http;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * @package DCarbone\PHPConsulAPI\Http
 */
class HttpAuth implements \JsonSerializable
{
    /** @var string */
    private $_username;
    /** @var string */
    private $_password;

    /**
     * ConsulHttpAuth constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password = null)
    {
        $this->_username = (string)$username;
        $this->_password = null === $password ? null : (string)$password;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['_username'];
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return ['username' => $this->_username];
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return string
     */
    public function compileAuthString()
    {
        return (string)$this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array('username' => $this->_username, 'password' => $this->_password);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return trim(sprintf('%s:%s', $this->_username, $this->_password), ":");
    }
}