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
use DCarbone\SimpleConsulPHP\KV\KVPair;

/**
 * Class Action
 * @package DCarbone\SimpleConsulPHP\KV\Verb
 */
abstract class AbstractKVVerb implements \Serializable, \JsonSerializable
{
    /** @var KVPair */
    protected $_KVPair;

    /**
     * AbstractKVVerb constructor.
     * @param KVPair $KVPair
     */
    public function __construct(KVPair $KVPair)
    {
        $this->_KVPair = $KVPair;
    }

    /**
     * @return string
     */
    abstract public function getVerb();

    /**
     * @return bool
     */
    abstract public function validate();

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_KVPair->getKey();
    }

    /**
     * @return KVPair
     */
    public function getKVPair()
    {
        return $this->_KVPair;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->_KVPair);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        if (($KVPair = unserialize($serialized)) instanceof KVPair)
        {
            $this->_KVPair = $KVPair;
        }
        else
        {
            throw new \DomainException(sprintf(
                '%s::unserialize - Invalid serialization data seen!',
                get_class($this)
            ));
        }
    }
}