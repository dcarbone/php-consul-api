<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class DecodedBody
 * @package DCarbone\PHPConsulAPI
 */
final class DecodedBody implements \ArrayAccess
{
    /** @var mixed */
    public $Decoded = null;
    /** @var \DCarbone\PHPConsulAPI\Error */
    public $Err;

    /**
     * DecodedBody constructor.
     * @param $decoded
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct($decoded, ?Error $err)
    {
        $this->Decoded = $decoded;
        $this->Err = $err;
    }

    /**
     * @return mixed
     */
    public function getDecoded()
    {
        return $this->Decoded;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Error
     */
    public function getErr(): \DCarbone\PHPConsulAPI\Error
    {
        return $this->Err;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return is_int($offset) && 0 <= $offset && $offset < 2;
    }

    /**
     * @param mixed $offset
     * @return \DCarbone\PHPConsulAPI\Error|mixed|null
     */
    public function offsetGet($offset)
    {
        if (0 === $offset) {
            return $this->Decoded;
        } elseif (1 === $offset) {
            return $this->Err;
        } else {
            throw new \OutOfBoundsException(sprintf('Offset %s does not exist', var_export($offset, true)));
        }
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException(sprintf('Cannot call method %s on class %s', __METHOD__, __CLASS__));
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException(sprintf('Cannot call method %s on class %s', __METHOD__, __CLASS__));
    }
}