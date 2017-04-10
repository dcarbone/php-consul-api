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
 * Class KVTree
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVTree implements \RecursiveIterator, \Countable, \JsonSerializable, \ArrayAccess, \Serializable {
    /** @var string */
    private $_prefix;

    /** @var \DCarbone\PHPConsulAPI\KV\KVTree[]|\DCarbone\PHPConsulAPI\KV\KVPair[] */
    private $_children = [];

    /**
     * KVTree constructor.
     * @param string $prefix
     */
    public function __construct($prefix) {
        $this->_prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getPrefix() {
        return $this->_prefix;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVTree|\DCarbone\PHPConsulAPI\KV\KVPair Can return any type.
     */
    public function current() {
        return current($this->_children);
    }

    /**
     * @return void Any returned value is ignored.
     */
    public function next() {
        next($this->_children);
    }

    /**
     * @return string scalar on success, or null on failure.
     */
    public function key() {
        return key($this->_children);
    }

    /**
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid() {
        return null !== key($this->_children);
    }

    /**
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        reset($this->_children);
    }

    /**
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren() {
        return $this->current() instanceof KVTree;
    }

    /**
     * @return \RecursiveIterator An iterator for the current entry.
     */
    public function getChildren() {
        return $this->current();
    }

    /**
     * @return int The custom count as an integer.
     */
    public function count() {
        return count($this->_children);
    }

    /**
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset) {
        if (is_string($offset)) {
            $subPath = str_replace($this->_prefix, '', $offset);
            $cnt = substr_count($subPath, '/');

            if (1 < $cnt || (1 === $cnt && '/' !== substr($subPath, -1))) {
                $childKey = $this->_prefix . substr($subPath, 0, strpos($subPath, '/') + 1);
                if (isset($this->_children[$childKey])) {
                    return isset($this->_children[$childKey][$offset]);
                }
            }
        }

        return isset($this->_children[$offset]) || array_key_exists($offset, $this->_children);
    }

    /**
     * @param mixed $offset The offset to retrieve.
     * @return \DCarbone\PHPConsulAPI\KV\KVTree|\DCarbone\PHPConsulAPI\KV\KVPair
     */
    public function offsetGet($offset) {
        if (is_string($offset)) {
            $subPath = str_replace($this->_prefix, '', $offset);
            $cnt = substr_count($subPath, '/');
            if (1 < $cnt || (1 === $cnt && '/' !== substr($subPath, -1))) {
                $childKey = $this->_prefix . substr($subPath, 0, strpos($subPath, '/') + 1);
                if (isset($this[$childKey])) {
                    return $this->_children[$childKey][$offset];
                }
            }
        }

        if (isset($this[$offset])) {
            return $this->_children[$offset];
        }

        trigger_error(sprintf(
            '%s - Requested offset %s does not exist in tree with prefix "%s".',
            get_class($this),
            $offset,
            $this->getPrefix()
        ));

        return null;
    }

    /**
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     * @return void
     */
    public function offsetSet($offset, $value) {
        if ('string' === gettype($offset)) {
            $subPath = str_replace($this->_prefix, '', $offset);
            $cnt = substr_count($subPath, '/');

            if (1 < $cnt || (1 === $cnt && '/' !== substr($subPath, -1))) {
                $childKey = $this->_prefix . substr($subPath, 0, strpos($subPath, '/') + 1);
                $this->_children[$childKey][$offset] = $value;
            } else {
                $this->_children[$offset] = $value;
            }
        } else if (null === $offset) {
            $this->_children[] = $value;
        } else {
            $this->_children[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset The offset to unset.
     * @return void
     */
    public function offsetUnset($offset) {
        // do nothing, yo...
    }

    /**
     * @return string the string representation of the object or null
     */
    public function serialize() {
        return serialize([$this->_prefix, $this->_children]);
    }

    /**
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized) {
        $data = unserialize($serialized);
        $this->_prefix = $data[0];
        $this->_children = $data[1];
    }

    /**
     * @return array data which can be serialized by json_encode,which is a value of any type other than a resource.
     */
    public function jsonSerialize() {
        $json = array($this->_prefix => []);
        foreach ($this->_children as $k => $child) {
            if ($child instanceof KVTree) {
                $json[$this->_prefix] = $child;
            } else if ($child instanceof KVPair) {
                $json[$this->_prefix][$child->Key] = $child;
            }
        }
        return $json;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->_prefix;
    }

    /**
     * @return array
     */
    public function __debugInfo() {
        return array(
            'prefix' => $this->_prefix,
            'children' => $this->_children
        );
    }
}