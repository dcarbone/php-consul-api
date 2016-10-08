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
abstract class AbstractCollection implements \JsonSerializable, \ArrayAccess, \Iterator, \Countable
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
     * @return array
     */
    public function __sleep()
    {
        return ['_storage'];
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->_storage;
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
     * @return mixed
     */
    public function current()
    {
        return current($this->_storage);
    }

    public function next()
    {
        next($this->_storage);
    }

    /**
     * @return string|int
     */
    public function key()
    {
        return key($this->_storage);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return null !== key($this->_storage);
    }

    public function rewind()
    {
        reset($this->_storage);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_storage[$offset]) || array_key_exists($offset, $this->_storage);
    }

    /**
     * @param string|int $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        if (isset($this[$offset]))
            return $this->_storage[$offset];

        $this->_triggerOutOfBoundsError($offset);

        return null;
    }

    /**
     * @param null|string|int $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset)
            $this->_storage[] = $value;
        else
            $this->_storage[$offset] = $value;
    }

    /**
     * @param string|int $offset
     */
    public function offsetUnset($offset)
    {
        if (isset($this[$offset]))
            unset($this->_storage[$offset]);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->_storage;
    }

    /**
     * @return int
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
            $regex = sprintf('{^.*%s.*$}i', substr($key, 0, 2));
            foreach($this as $k => $_)
            {
                if (preg_match($regex, $k))
                    $possibleMatches[] = $k;
            }
        }
        return $possibleMatches;
    }
}