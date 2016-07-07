<?php namespace DCarbone\PHPConsulAPI;

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
 * Class Error
 * @package DCarbone\PHPConsulAPI
 */
class Error implements \JsonSerializable
{
    /** @var array */
    private $_levels = array(
        'debug',
        'info',
        'warn',
        'error',
    );

    /** @var string */
    private $_level;

    /** @var DateTime */
    private $_timestamp;

    /** @var string */
    private $_message;

    /**
     * Error constructor.
     * @param string $level
     * @param string $message
     */
    public function __construct($level, $message)
    {
        if (!is_string($level) || !in_array($level, $this->_levels, true))
        {
            throw new \InvalidArgumentException(sprintf(
                '%s - "%s" is not valid level.  Available choices: ["%s"]',
                get_class($this),
                is_string($level) ? $level : gettype($level),
                implode('", "', $this->_levels)
            ));
        }

        $this->_level = $level;
        $this->_timestamp = new DateTime();
        $this->_message = $message;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return array(
            'level' => $this->_level,
            'message' => $this->_message,
            'timestamp' => $this->_timestamp
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '[%s] - %s - %s',
            $this->_level,
            $this->_timestamp,
            $this->_message
        );
    }
}