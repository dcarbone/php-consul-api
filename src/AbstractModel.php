<?php namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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
abstract class AbstractModel implements \JsonSerializable {

    /**
     * AbstractModel constructor.
     *
     * Convenience method to help set scalar types.  Any extending class must have a constructor that builds any
     * array / object properties it may have.
     *
     * @param array $data
     */
    public function __construct(array $data = []) {
        foreach ($data as $k => $v) {
            $this->{$k} = $v;
        }
    }

    /**
     * TODO: More specific omission of values?
     *
     * Produces equivalent of `json:",omitempty"` tag
     *
     * @return array
     */
    function jsonSerialize() {
        return array_filter((array)$this);
    }

    /**
     * @return string
     */
    public function __toString() {
        return get_class($this);
    }
}