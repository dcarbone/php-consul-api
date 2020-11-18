<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class AbstractModels
 * @package DCarbone\PHPConsulAPI
 */
abstract class AbstractModels implements \Iterator, \ArrayAccess, \Countable, \JsonSerializable {
    /** @var \DCarbone\PHPConsulAPI\AbstractModel[] */
    protected $_list = [];

    /** @var null|string */
    protected $containedClass = null;

    /**
     * AbstractModels constructor.
     * @param array $children
     */
    public function __construct($children = []) {
        if (is_array($children)) {
            foreach (array_filter($children) as $child) {
                if (is_array($child)) {
                    $this->_list[] = $this->newChild($child);
                } else if ($child instanceof $this->containedClass) {
                    $this->_list[] = $child;
                } else {
                    throw new \InvalidArgumentException(sprintf(
                        get_class($this).' accepts only '.$this->containedClass.' as a child, saw %s',
                        is_object($child) ? get_class($child) : gettype($child)));
                }
            }
        } else if (null === $children) {
            // do nothin
        } else {
            throw new \InvalidArgumentException(get_class($this).
                '::__construct accepts only array of '.
                $this->containedClass.
                '\'s or null as values');
        }
    }

    /**
     * @param \DCarbone\PHPConsulAPI\AbstractModel|null $value
     */
    public function append(AbstractModel $value = null) {
        if (null === $value || $value instanceof $this->containedClass) {
            $this->_list[] = $value;
        } else {
            throw new \InvalidArgumentException(get_class($this).
                ' accepts only objects of type '.
                $this->containedClass.
                ' or null as values');
        }
    }

    public function current() {
        return current($this->_list);
    }

    public function next() {
        next($this->_list);
    }

    /**
     * @return int|null
     */
    public function key() {
        return key($this->_list);
    }

    /**
     * @return bool
     */
    public function valid() {
        return null !== key($this->_list);
    }

    public function rewind() {
        reset($this->_list);
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset) {
        return is_int($offset) && isset($this->_list[$offset]);
    }

    public function offsetGet($offset) {
        if (is_int($offset) && isset($this->_list[$offset])) {
            return $this->_list[$offset];
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
        if (null !== $value && !($value instanceof $this->containedClass)) {
            throw new \InvalidArgumentException('Value must be instance of '.$this->containedClass);
        }
        $this->_list[$offset] = $value;
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset) {
        unset($this->_list[$offset]);
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->_list);
    }

    public function jsonSerialize() {
        return $this->_list;
    }

    /**
     * @param $data
     * @return mixed
     */
    abstract protected function newChild($data): AbstractModel;
}