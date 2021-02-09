<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class AbstractValuedResponse
 * @package DCarbone\PHPConsulAPI
 */
abstract class AbstractValuedResponse extends AbstractResponse implements ValuedResponseInterface
{
    use ErrorContainer;

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return \is_int($offset) && 0 <= $offset && $offset < 2;
    }

    /**
     * @param mixed $offset
     * @return \DCarbone\PHPConsulAPI\Error|mixed|null
     */
    public function offsetGet($offset)
    {
        if (0 === $offset) {
            return $this->getValue();
        }
        if (1 === $offset) {
            return $this->Err;
        }
        throw $this->_newOutOfRangeException($offset);
    }
}
