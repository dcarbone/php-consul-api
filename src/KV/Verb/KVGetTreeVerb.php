<?php namespace DCarbone\SimpleConsulPHP\KV\Verb;

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
 * Class KVGetTreeVerb
 * @package DCarbone\SimpleConsulPHP\KV\Verb
 */
class KVGetTreeVerb extends AbstractKVVerb implements \Serializable
{
    /** @var string */
    private $_prefix;

    /**
     * KVGetTreeVerb constructor.
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        $this->_prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return 'get-tree';
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->_prefix);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->_prefix = unserialize($serialized);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return array(
            'Verb' => $this->getVerb(),
            'Key' => $this->_prefix,
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->_prefix;
    }
}