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
final class RequestResponse
{
    /** @var \DCarbone\PHPConsulAPI\RequestMeta */
    public RequestMeta $RequestMeta;
    /** @var \DCarbone\Go\Time\Duration */
    public Time\Duration $Duration;
    /** @var \Psr\Http\Message\ResponseInterface|null */
    public ?ResponseInterface $Response;
    /** @var \DCarbone\PHPConsulAPI\Error|null */
    public ?Error $Err;

    /**
     * RequestResponse constructor.
     * @param \DCarbone\PHPConsulAPI\RequestMeta $meta
     * @param \DCarbone\Go\Time\Duration $dur
     * @param \Psr\Http\Message\ResponseInterface|null $resp
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(RequestMeta $meta, Time\Duration $dur, ?ResponseInterface $resp, ?Error $err)
    {
        $this->RequestMeta = $meta;
        $this->Duration    = $dur;
        $this->Response    = $resp;
        $this->Err         = $err;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\RequestMeta
     */
    public function getRequestMeta(): RequestMeta
    {
        return $this->RequestMeta;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getDuration(): Time\Duration
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
}
