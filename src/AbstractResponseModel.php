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
 * Class AbstractResponseModel
 * @package DCarbone\SimpleConsulPHP\Base
 */
abstract class AbstractResponseModel extends AbstractCollection
{
    /** @var array */
    protected static $default = array();

    /**
     * AbstractResponseModel constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        parent::__construct($data + static::$default);
    }

    /**
     * @param string $json
     * @return static
     */
    public static function fromJson($json)
    {
        return new static(json_decode($json, true));
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static($data);
    }
}