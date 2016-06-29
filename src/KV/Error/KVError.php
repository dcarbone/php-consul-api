<?php namespace DCarbone\SimpleConsulPHP\KV\Error;

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

use DCarbone\SimpleConsulPHP\Base\DateTime;
use DCarbone\SimpleConsulPHP\KV\KVPair;

/**
 * Class KVVerbValidationError
 * @package DCarbone\SimpleConsulPHP\KV\Verb
 */
class KVError implements \JsonSerializable
{
    const VALIDATION = 'VALIDATION';
    const TRANSACTION_OVERFLOW = 'TRANSACTION_OVERFLOW';

    /** @var KVPair */
    private $_KVPair;
    /** @var string */
    private $_message;
    /** @var DateTime */
    private $_timestamp;
    /** @var string */
    private $_type;

    /**
     * KVVerbValidationError constructor.
     * @param KVPair $KVPair
     * @param string $message
     * @param string $type
     * @param bool $triggerError
     */
    public function __construct(KVPair $KVPair, $message, $type, $triggerError = true)
    {
        $this->_KVPair = $KVPair;
        $this->_message = $message;
        $this->_type = $type;
        $this->_timestamp = new DateTime();

        if ($triggerError)
            trigger_error($message, E_USER_ERROR);
    }

    /**
     * @return KVPair
     */
    public function getKVPair()
    {
        return $this->_KVPair;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @return DateTime
     */
    public function getTimestamp()
    {
        return $this->_timestamp;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return array(
            'KVPair' => $this->_KVPair,
            'Message' => $this->_message,
            'Type' => $this->_type,
            'Timestamp' => $this->_timestamp
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('[%s] - %s - %s', $this->_timestamp->defaultFormat(), $this->_type, $this->_message);
    }
}