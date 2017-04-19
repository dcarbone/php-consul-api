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
    private $time;

    /** @var string */
    private $message;

    /**
     * Error constructor.
     * @param string $message
     */
    public function __construct($message) {
        $this->time = new DateTime();
        $this->message = $message;
    }

    /**
     * @return DateTime
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'message' => $this->message,
            'timestamp' => $this->time
        ];
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->message;
    }
}