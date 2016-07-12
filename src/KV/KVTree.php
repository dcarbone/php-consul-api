<?php namespace DCarbone\PHPConsulAPI\KV;

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
 * Class KVTree
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVTree implements \RecursiveIterator, \Countable, \JsonSerializable, \ArrayAccess
{
    /** @var string */
    private $_prefix;

    /** @var KVTree[]|KVPair[] */
    private $_children = array();

    /**
     * KVTree constructor.
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        $this->_prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return KVTree|KVPair Can return any type.
     */
    public function current()
    {
        return current($this->_children);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->_children);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return string scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_children);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return null !== key($this->_children);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_children);
    }

    /**
     * Returns if an iterator can be created for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren()
    {
        return $this->current() instanceof KVTree;
    }

    /**
     * Returns an iterator for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.getchildren.php
     * @return \RecursiveIterator An iterator for the current entry.
     */
    public function getChildren()
    {
        return $this->current();
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->_children);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by json_encode,which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        $json = array($this->_prefix => array());
        foreach($this->_children as $k=>$child)
        {
            if ($child instanceof KVTree)
                $json[$this->_prefix] = $child;
            else if ($child instanceof KVPair)
                $json[$this->_prefix][$child->Key] = $child;
            else
                $json[$this->_prefix][$k] = $child;
        }
        return $json;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        if (is_string($offset))
        {
            $subPath = trim(str_replace($this->_prefix, '', $offset), "/");
            $slashPos = strpos($subPath, '/');
            if (false === $slashPos)
            {
                $offset = $subPath;
            }
            else
            {
                $childKey = substr($offset, 0, $slashPos);
                if (isset($this->_children[$childKey]))
                    return isset($this->_children[$childKey][$offset]);
            }
        }

        return isset($this->_children[$offset]) || array_key_exists($offset, $this->_children);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset The offset to retrieve.
     * @return KVTree|KVPair
     */
    public function offsetGet($offset)
    {
        if (is_string($offset))
        {
            $subPath = trim(str_replace($this->_prefix, '', $offset), "/");
            $slashPos = strpos($subPath, '/');
            if (false === $slashPos)
            {
                $offset = $subPath;
            }
            else
            {
                $childKey = substr($subPath, 0, $slashPos);
                if (isset($this[$childKey]))
                    return $this->_children[$childKey][$offset];
            }
        }

        if (isset($this[$offset]))
            return $this->_children[$offset];

        trigger_error(sprintf(
            '%s - Requested offset %s does not exist in tree with prefix "%s".',
            get_class($this),
            $offset,
            $this->getPrefix()
        ));

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
        switch(gettype($offset))
        {
            case 'NULL':
                $this->_children[] = $value;
                break;
            case 'integer':
            case 'double':
                $this->_children[$offset] = $value;
                break;
            case 'string':
                $childPath = trim(str_replace($this->_prefix, '', $offset), "/");
                $slashPos = strpos($childPath, '/');

                if (false === $slashPos)
                    $this->_children[$childPath] = $value;
                else
                    $this->_children[substr($childPath, 0, $slashPos)][$offset] = $value;
        }
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset The offset to unset.
     * @return void
     */
    public function offsetUnset($offset)
    {
        // do nothing, yo...
    }
}