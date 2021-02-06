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

use DCarbone\Go\Time;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequestResponse
 */
final class RequestResponse implements \ArrayAccess
{
    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $Duration = null;
    /** @var \Psr\Http\Message\ResponseInterface|null */
    public ?ResponseInterface $Response = null;
    /** @var \DCarbone\PHPConsulAPI\Error|null */
    public ?Error $Err = null;

    /**
     * RequestResponse constructor.
     * @param \DCarbone\Go\Time\Duration $durf
     * @param \Psr\Http\Message\ResponseInterface|null $resp
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(Time\Duration $durf, ?ResponseInterface $resp, ?Error $err)
    {
        $this->Duration = $durf;
        $this->Response = $resp;
        $this->Err      = $err;
    }

    /**
     * @return \DCarbone\Go\Time\Duration|null
     */
    public function getDuration(): ?Time\Duration
    {
        return $this->Duration;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->Response;
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
    public function offsetExists($offset): bool
    {
        return \is_int($offset) && $offset >= 0 && $offset < 3;
    }

    /**
     * @param mixed $offset
     * @return \DCarbone\Go\Time\Duration|\DCarbone\PHPConsulAPI\Error|mixed|\Psr\Http\Message\ResponseInterface|null
     */
    public function offsetGet($offset)
    {
        if (0 === $offset) {
            return $this->Duration;
        }
        if (1 === $offset) {
            return $this->Response;
        }
        if (2 === $offset) {
            return $this->Err;
        }
        throw new \OutOfBoundsException(\sprintf('Offset %s does not exist', \var_export($offset, true)));
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        throw new \BadMethodCallException(\sprintf('Cannot call method %s on class %s', __METHOD__, __CLASS__));
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        throw new \BadMethodCallException(\sprintf('Cannot call method %s on class %s', __METHOD__, __CLASS__));
    }
}
