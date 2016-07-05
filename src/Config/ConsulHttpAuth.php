<?php namespace DCarbone\SimpleConsulPHP\Config;

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
 * Class ConsulHttpAuth
 * @package DCarbone\SimpleConsulPHP\Config
 */
class ConsulHttpAuth implements \Serializable, \JsonSerializable
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
        $this->_username = $username;
        $this->_password = $password;
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
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(array($this->_username, $this->_password));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        if (2 <= count($data))
        {
            $this->_username = $data[0];
            $this->_password = $data[1];
        }
        else
        {
            throw new \DomainException(sprintf(
                '%s - Invalid serialized input detected.',
                get_class($this)
            ));
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
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
        return sprintf('%s:%s', $this->_username, $this->_password);
    }
}