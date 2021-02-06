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
 */
abstract class AbstractValuedResponse
{
    use ResponseErrorTrait;

    /**
     * AbstractValuedResponse constructor.
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(?Error $err)
    {
        $this->Err = $err;
    }

    /**
     * @return mixed
     */
    abstract public function getValue();

    /**
     * @param \$offset
     * @param \$value
     */
    public function offsetSet($offset, $value): void
    {
        throw new \BadMethodCallException(\sprintf('Cannot call %s on %s', __METHOD__, \get_called_class()));
    }

    /**
     * @param \$offset
     */
    public function offsetUnset($offset): void
    {
        throw new \BadMethodCallException(\sprintf('Cannot call %s on %s', __METHOD__, \get_called_class()));
    }
}
