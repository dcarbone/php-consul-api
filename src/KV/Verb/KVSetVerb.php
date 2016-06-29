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
 * Class KVSetVerb
 * @package DCarbone\SimpleConsulPHP\KV\Verb
 */
class KVSetVerb extends AbstractKVVerb
{
    /** @var string */
    private $_key = null;
    /** @var mixed */
    private $_value = null;
    /** @var array */
    private $_flags = 0;

    /**
     * KVSetVerb constructor.
     * @param string $key
     * @param mixed $value
     * @param array $flags
     */
    public function __construct($key = null, $value = null, $flags = null)
    {
        $this->_key = $key;
        $this->_value = $value;
        $this->_flags = $flags;
    }

    /**
     * @param KVPair $KVPair
     * @return static
     */
    public static function createFromKVPair(KVPair $KVPair)
    {
        return new static($KVPair->getKey(), $KVPair->getValue(), $KVPair->getFlags());
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return 'set';
    }

    /**
     * @return bool
     */
    public function validate()
    {

    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->_key = $key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        return $this->_flags;
    }

    /**
     * @param array $flags
     */
    public function setFlags($flags)
    {
        $this->_flags = $flags;
    }
}