<?php namespace DCarbone\SimpleConsulPHP\Base;

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
 * Class DateTime
 * @package DCarbone\SimpleConsulPHP\Base
 */
class DateTime extends \DateTime implements \JsonSerializable
{
    /** @var string */
    private static $_defaultFormat = 'Y-m-d\TH:i:s.uO';

    /**
     * @param string $format
     */
    public static function setDefaultFormat($format)
    {
        if (!is_string($format))
        {
            throw new \InvalidArgumentException(sprintf(
                'SimpleConsulPHP - DateTime::setDefaultFormat expects argument 1 to be string, %s seen.',
                gettype($format)
            ));
        }

        if (false === @date($format))
        {
            throw new \InvalidArgumentException(sprintf(
                'SimpleConsulPHP - DateTime::setDefaultFormat specified invalid format "%s".  Please see http://php.net/manual/en/function.date.php.',
                $format
            ));
        }

        self::$_defaultFormat = $format;
    }

    /**
     * @return string
     */
    public function defaultFormat()
    {
        return $this->format(self::$_defaultFormat);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return (string)$this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format(self::$_defaultFormat);
    }
}