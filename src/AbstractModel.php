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
 * Class AbstractModel
 * @package DCarbone\PHPConsulAPI
 */
abstract class AbstractModel implements \JsonSerializable
{
    /**
     * AbstractObjectModel constructor.
     *
     * Convenience method to help set scalar types.  For object types, it is recommended
     * that the implementing class have it's own constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach($data as $k => $v)
        {
            $this->{$k} = $v;
        }
    }

    /**
     * @return array
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