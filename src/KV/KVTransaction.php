<?php namespace DCarbone\SimpleConsulPHP\KV;

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

use DCarbone\SimpleConsulPHP\KV\Verb\AbstractKVVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVCheckAndSetVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVCheckIndexVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVCheckSessionVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVDeleteCheckAndSetVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVDeleteTreeVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVDeleteVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVGetTreeVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVGetVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVLockVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVSetVerb;
use DCarbone\SimpleConsulPHP\KV\Verb\KVUnlockVerb;

/**
 * Class KVTransaction
 * @package DCarbone\SimpleConsulPHP\KV
 */
class KVTransaction implements \Iterator, \Countable, \Serializable
{
    /** @var AbstractKVVerb[] */
    private $_verbs = array();

    /** @var KVClient */
    private $_KVClient;

    /**
     * KVTransaction constructor.
     * @param KVClient $KVClient
     */
    public function __construct(KVClient $KVClient)
    {
        $this->_KVClient = $KVClient;
    }

    /**
     * @param AbstractKVVerb $verb
     * @return $this
     */
    public function addVerb(AbstractKVVerb $verb)
    {
        if (64 > count($this))
        {
            $this->_verbs[] = $verb;
        }
        else
        {
            trigger_error(
                sprintf(
                    '%s::addVerb - Maximum transaction length of 64 reached, will not add %s verb for %s key.',
                    get_class($this),
                    $verb->getVerb(),
                    (string)$verb
                ),
                E_USER_ERROR
            );
        }
        return $this;
    }

    /**
     * @param KVPair|null $KVPair
     * @return KVSetVerb
     */
    public function set(KVPair $KVPair)
    {
        $this->_verbs[] = new KVSetVerb($KVPair);
        return end($this->_verbs);
    }

//    /**
//     * @param KVPair|null $KVPair
//     * @return KVCheckAndSetVerb
//     */
//    public function checkAndSet(KVPair $KVPair)
//    {
//        $this->_verbs[] = new KVCheckAndSetVerb($KVPair);
//        return end($this->_verbs);
//    }

    /**
     * @param KVPair|null $KVPair
     * @return KVLockVerb
     */
    public function lock(KVPair $KVPair)
    {
        $this->_verbs[] = new KVLockVerb($KVPair);
        return end($this->_verbs);
    }

    /**
     * @param KVPair $KVPair
     * @return KVUnlockVerb
     */
    public function unlock(KVPair $KVPair)
    {
        $this->_verbs[] = new KVUnlockVerb($KVPair);
        return end($this->_verbs);
    }

    /**
     * @param KVPair $KVPair
     * @return KVGetVerb
     */
    public function get(KVPair $KVPair)
    {
        $this->_verbs[] = new KVGetVerb($KVPair);
        return end($this->_verbs);
    }

    /**
     * @param string $prefix
     * @return KVGetTreeVerb
     */
    public function getTree($prefix)
    {
        $this->_verbs[] = new KVGetTreeVerb($prefix);
        return end($this->_verbs);
    }

    /**
     * @param KVPair $KVPair
     * @return KVCheckIndexVerb
     */
    public function checkIndex(KVPair $KVPair)
    {
        $this->_verbs[] = new KVCheckIndexVerb($KVPair);
        return end($this->_verbs);
    }

    /**
     * @param KVPair $KVPair
     * @return KVCheckSessionVerb
     */
    public function checkSession(KVPair $KVPair)
    {
        $this->_verbs[] = new KVCheckSessionVerb($KVPair);
        return end($this->_verbs);
    }

    /**
     * @param KVPair $KVPair
     * @return KVDeleteVerb
     */
    public function delete(KVPair $KVPair)
    {
        $this->_verbs[] = new KVDeleteVerb($KVPair);
        return end($this->_verbs);
    }

    /**
     * @param string $prefix
     * @return KVDeleteTreeVerb
     */
    public function deleteTree($prefix)
    {
        $this->_verbs[] = new KVDeleteTreeVerb($prefix);
        return end($this->_verbs);
    }

//    /**
//     * @param KVPair $KVPair
//     * @return KVDeleteCheckAndSetVerb
//     */
//    public function deleteCheckAndSet(KVPair $KVPair)
//    {
//        $this->_verbs[] = new KVDeleteCheckAndSetVerb($KVPair);
//        return end($this->_verbs);
//    }

    public function execute()
    {

    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return AbstractKVVerb Can return any type.
     */
    public function current()
    {
        return current($this->_verbs);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->_verbs);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_verbs);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return null !== key($this->_verbs);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_verbs);
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->_verbs);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->_verbs = unserialize($serialized);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->_verbs);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by json_encode which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->_verbs;
    }
}