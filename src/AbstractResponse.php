<?php

declare(strict_types=1);

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
 * Class AbstractResponse
 */
abstract class AbstractResponse implements \ArrayAccess
{
    /**
     * @param mixed $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new \BadMethodCallException(sprintf('Calling %s on class %s is forbidden', __METHOD__, static::class));
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \BadMethodCallException(sprintf('Calling %s on class %s is forbidden', __METHOD__, static::class));
    }

    /**
     * @param mixed $offset
     * @return \OutOfRangeException
     */
    protected function _newOutOfRangeException(mixed $offset): \OutOfRangeException
    {
        return new \OutOfRangeException(sprintf('Offset %s does not exist', $offset));
    }
}
