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
 * Class Values
 * @package DCarbone\PHPConsulAPI
 */
class Values implements \JsonSerializable, \Countable {
    /** @var array */
    private $values = [];

    /**
     * @param string $key
     * @return string
     */
    public function get($key) {
        if (!is_string($key)) {
            throw new \InvalidArgumentException(sprintf('$key must be string, saw "%s".', gettype($key)));
        }

        if (isset($this->values[$key])) {
            return $this->values[$key][0];
        }

        return '';
    }

    /**
     * @param string $key
     * @return string[]
     */
    public function getAll($key) {
        if (!is_string($key)) {
            throw new \InvalidArgumentException(sprintf('$key must be string, saw "%s".', gettype($key)));
        }

        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        return [];
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function set($key, $value) {
        if (!is_string($key)) {
            throw new \InvalidArgumentException(sprintf('$key must be string, saw "%s".', gettype($key)));
        }

        if (settype($value, 'string')) {
            $this->values[$key] = [$value];
        } else {
            throw new \InvalidArgumentException(sprintf('$value must be castable to string, saw "%s".',
                gettype($value)));
        }
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function add($key, $value) {
        if (!is_string($key)) {
            throw new \InvalidArgumentException(sprintf('$key must be string, saw "%s".', gettype($key)));
        }

        if (settype($value, 'string')) {
            if (isset($this->values[$key])) {
                $this->values[$key][] = $value;
            } else {
                $this->values[$key] = [$value];
            }
        } else {
            throw new \InvalidArgumentException(sprintf('$value must be castable to string, saw "%s".',
                gettype($value)));
        }
    }

    /**
     * @param string $key
     */
    public function delete($key) {
        if (!is_string($key)) {
            throw new \InvalidArgumentException(sprintf('$key must be string, saw "%s".', gettype($key)));
        }

        unset($this->values[$key]);
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->values);
    }

    /**
     * @return array
     */
    public function toPsr7Array() {
        return $this->values;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return $this->values;
    }

    /**
     * @return string
     */
    public function __toString() {
        $str = '';
        foreach ($this->values as $k => $vs) {
            foreach ($vs as $v) {
                if ('' !== $str) {
                    $str .= '&';
                }
                if ('' === $v) {
                    $str .= $k;
                } else {
                    $str .= sprintf('%s=%s', $k, $this->encode($v));
                }
            }
        }
        return $str;
    }

    /**
     * @param string $v
     * @return string mixed
     */
    protected function encode($v) {
        return $v;
    }
}