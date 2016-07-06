<?php namespace DCarbone\SimpleConsulPHP;

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
 * TODO: I really do not like this class.  Think of a better way to handle this.
 *
 * Class AbstractDefinedCollection
 * @package DCarbone\SimpleConsulPHP\Base
 */
abstract class AbstractDefinedCollection implements \Serializable, \JsonSerializable, \ArrayAccess, \Iterator
{
    /** @var array */
    protected $_storage = array();

    /**
     * AbstractConsulConfig constructor.
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
            return $this->{sprintf('get%s', $offset)}();

        throw $this->_createOutOfBoundsException($offset);
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
        if (isset($this[$offset]))
            $this->{sprintf('set%s', $offset)}($value);
        else
            throw $this->_createOutOfBoundsException($offset);
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
            $this->_storage[$offset] = null;
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
        foreach(unserialize($serialized) as $k => $v)
        {
            $this[$k] = $v;
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return array_filter($this->_storage);
    }

    /**
     * @param mixed $key
     * @return \OutOfBoundsException
     */
    private function _createOutOfBoundsException($key)
    {
        $matches = $this->_findKeyMatches($key);
        if (0 === count($matches))
        {
            return new \OutOfBoundsException(sprintf(
                '%s - "%s" is not a property on this object.',
                get_class($this),
                is_string($key) ? $key : gettype($key)
            ));
        }

        return new \OutOfBoundsException(sprintf(
            '%s - "%s" is not a property on this object. Did you mean one of the following: ["%s"]',
            get_class($this),
            $key,
            implode('", "', $matches)
        ));
    }

    /**
     * @param mixed $key
     * @return array
     */
    private function _findKeyMatches($key)
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