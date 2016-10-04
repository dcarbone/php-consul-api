<?php namespace DCarbone\PHPConsulAPI\Model;

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
 * Class AbstractModel
 * @package DCarbone\PHPConsulAPI\Model
 */
abstract class AbstractModel implements \JsonSerializable
{
    /**
     * AbstractObjectModel constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach($data as $k => $v)
        {
            $this->{$k} = $v;
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return array_filter((array)$this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
}