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
 * TODO: I am not a huge fan of this collection stack.  Find a better way to do this.
 *
 * Class AbstractCollection
 * @package DCarbone\PHPConsulAPI
 */
abstract class AbstractCollection implements \JsonSerializable, \Serializable, \ArrayAccess, \Iterator, \Countable
{
    /** @var array */
    protected $_storage = array();

    /**
     * AbstractResponseModel constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach($data as $k => $v)
        {
            $this[$k] = $v;
        }
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data = array())
    {
        $obj = new static;
        foreach($data as $k => $v)
        {
            $obj[$k] = $v;
        }
        return $obj;
    }

    /**
     * @return mixed
     */
    public function reset()
    {
        return reset($this->_storage);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this);
    }

    /**
     * @return array
     */
    public function keys()
    {
        return array_keys($this->_storage);
    }

    /**
     * @return array
     */
    public function values()
    {
        return array_values($this->_storage);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->_storage);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->_storage);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_storage);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return null !== key($this->_storage);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_storage);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return isset($this->_storage[$offset]) || array_key_exists($offset, $this->_storage);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if (isset($this[$offset]))
            return $this->_storage[$offset];

        $this->_triggerOutOfBoundsError($offset);

        return null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset)
            $this->_storage[] = $value;
        else
            $this->_storage[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset The offset to unset.
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (isset($this[$offset]))
            unset($this->_storage[$offset]);
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->_storage);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->_storage = unserialize($serialized);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->_storage;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->_storage);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }

    /**
     * @param mixed $key
     * @param int $level
     */
    protected function _triggerOutOfBoundsError($key, $level = E_USER_NOTICE)
    {
        $matches = $this->_findKeyMatches($key);
        if (0 === count($matches))
        {
            trigger_error(
                sprintf(
                    '%s - "%s" is not a property on this object.',
                    get_class($this),
                    is_string($key) ? $key : gettype($key)
                ),
                $level
            );
        }
        else
        {
            trigger_error(
                sprintf(
                    '%s - "%s" is not a property on this object. Did you mean one of the following: ["%s"]?',
                    get_class($this),
                    $key,
                    implode('", "', $matches)
                ),
                $level
            );
        }
    }

    /**
     * @param mixed $key
     * @return array
     */
    protected function _findKeyMatches($key)
    {
        $possibleMatches = array();
        if (is_string($key))
        {
            $regex = sprintf('{^.*%s.*$}i', $key);
            foreach($this as $k => $_)
            {
                if (preg_match($regex, $k))
                    $possibleMatches[] = $k;
            }
        }
        return $possibleMatches;
    }
}