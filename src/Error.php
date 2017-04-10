<?php namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * TODO: Make this better...
 *
 * Class Error
 * @package DCarbone\PHPConsulAPI
 */
class Error implements \JsonSerializable {
    /** @var DateTime */
    private $_timestamp;

    /** @var string */
    private $_message;

    /**
     * Error constructor.
     * @param string $message
     */
    public function __construct($message) {
        $this->_timestamp = new DateTime();
        $this->_message = $message;
    }

    /**
     * @return DateTime
     */
    public function getTimestamp() {
        return $this->_timestamp;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->_message;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    public function jsonSerialize() {
        return array(
            'message' => $this->_message,
            'timestamp' => $this->_timestamp
        );
    }

    /**
     * @return string
     */
    public function __toString() {
        return sprintf(
            '[error] - %s - %s',
            $this->_timestamp,
            $this->_message
        );
    }
}