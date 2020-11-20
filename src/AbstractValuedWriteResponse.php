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
 * Class AbstractValuedWriteResponse
 * @package DCarbone\PHPConsulAPI
 */
abstract class AbstractValuedWriteResponse implements \ArrayAccess
{
    /** @var \DCarbone\PHPConsulAPI\WriteMeta|null */
    public $WriteMeta = null;
    /** @var \DCarbone\PHPConsulAPI\Error|null */
    public $Err = null;

    /**
     * AbstractValuedWriteResponse constructor.
     * @param \DCarbone\PHPConsulAPI\WriteMeta|null $wm
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(?WriteMeta $wm, ?Error $err)
    {
        $this->WriteMeta = $wm;
        $this->Err = $err;
    }

    /**
     * @return mixed
     */
    abstract public function getValue();

    /**
     * @return \DCarbone\PHPConsulAPI\WriteMeta|null
     */
    public function getWriteMeta(): ?WriteMeta
    {
        return $this->WriteMeta;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public function getErr(): ?Error
    {
        return $this->Err;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return is_int($offset) && 0 <= $offset && $offset < 3;
    }

    /**
     * @param mixed $offset
     * @return \DCarbone\PHPConsulAPI\Error|\DCarbone\PHPConsulAPI\WriteMeta|mixed|null
     */
    public function offsetGet($offset)
    {
        if (0 === $offset) {
            return $this->getValue();
        } elseif (1 === $offset) {
            return $this->WriteMeta;
        } elseif (2 === $offset) {
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
        throw new \BadMethodCallException(sprintf('Cannot call %s on %s', __METHOD__, __CLASS__));
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException(sprintf('Cannot call %s on %s', __METHOD__, __CLASS__));
    }
}