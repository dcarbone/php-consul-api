<?php namespace DCarbone\PHPConsulAPI\KV;

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
 * Class KVPairs
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVPairs implements \Iterator, \ArrayAccess, \Countable, \JsonSerializable {
    private $_kvps = [];

    /**
     * KVPairs constructor.
     * @param array $kvps
     */
    public function __construct($kvps = []) {
        if (is_array($kvps)) {
            foreach (array_filter($kvps) as $kvp) {
                if (is_array($kvp)) {
                    $this->_kvps[] = new KVPair($kvp, true);
                } else if ($kvp instanceof KVPair) {
                    $this->_kvps[] = $kvp;
                } else {
                    throw new \InvalidArgumentException(sprintf(
                        'KVPairs accepts only KVPair as a child, saw %s',
                        is_object($kvp) ? get_class($kvp) : gettype($kvp)));
                }
            }
        } else if (null === $kvps) {
            // do nothin
        } else {
            throw new \InvalidArgumentException('KVPairs::__construct accepts only array of KVPair\'s or null as values');
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function current() {
        return current($this->_kvps);
    }

    public function next() {
        next($this->_kvps);
    }

    /**
     * @return int|null
     */
    public function key() {
        return key($this->_kvps);
    }

    /**
     * @return bool
     */
    public function valid() {
        return null !== key($this->_kvps);
    }

    public function rewind() {
        reset($this->_kvps);
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset) {
        return is_int($offset) && isset($this->_kvps[$offset]);
    }

    /**
     * @param int $offset
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function offsetGet($offset) {
        if (is_int($offset) && isset($this->_kvps[$offset])) {
            return $this->_kvps[$offset];
        }
        throw new \OutOfRangeException(sprintf(
            'Offset %s does not exist in this list',
            is_int($offset) ? (string)$offset : gettype($offset)
        ));
    }

    /**
     * @param int $offset
     * @param \DCarbone\PHPConsulAPI\KV\KVPair $value
     */
    public function offsetSet($offset, $value) {
        if (!is_int($offset)) {
            throw new \InvalidArgumentException('Offset must be int');
        }
        if (null !== $value && !($value instanceof KVPair)) {
            throw new \InvalidArgumentException('Value must be instance of KVPair');
        }
        $this->_kvps[$offset] = $value;
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset) {
        unset($this->_kvps[$offset]);
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->_kvps);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthCheck[]
     */
    public function jsonSerialize() {
        return $this->_kvps;
    }
}