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

    /**
     * Construct a new QueryMeta instance based on this response.
     *
     * @return \DCarbone\PHPConsulAPI\QueryMeta
     */
    public function buildQueryMeta(): QueryMeta
    {
        // init class
        $qm = new QueryMeta();

        // set some always-defined values
        $qm->RequestTime = $this->Duration;
        $qm->RequestUrl  = (string)$this->RequestMeta->uri;

        // if there was no response, return as-is
        // note: should never see this in the wild.
        if (!isset($this->Response)) {
            return $qm;
        }

        // populate query meta fields based on response

        if ('' !== ($h = $this->Response->getHeaderLine(Consul::_headerConsulIndex))) {
            $qm->LastIndex = (int)$h;
        }

        $qm->LastContentHash = $this->Response->getHeaderLine(Consul::_headerConsulContentHash);

        // note: do not need to check both as guzzle response compares headers insensitively
        if ('' !== ($h = $this->Response->getHeaderLine(Consul::_headerConsulKnownLeader))) {
            $qm->KnownLeader = (bool)$h;
        }
        // note: do not need to check both as guzzle response compares headers insensitively
        if ('' !== ($h = $this->Response->getHeaderLine(Consul::_headerConsulLastContact))) {
            $qm->LastContact = (int)$h;
        }

        if ('' !== ($h = $this->Response->getHeaderLine(Consul::_headerConsulTranslateAddresses))) {
            $qm->AddressTranslationEnabled = (bool)$h;
        }

        if ('' !== ($h = $this->Response->getHeaderLine(Consul::_headerCache))) {
            $qm->CacheAge = Time::Duration(\intval($h, 10) * Time::Second);
        }

        return $qm;
    }

    /**
     * Construct a new WriteMeta instance based on this response.
     *
     * @return \DCarbone\PHPConsulAPI\WriteMeta
     */
    public function buildWriteMeta(): WriteMeta
    {
        $wm              = new WriteMeta();
        $wm->RequestTime = $this->Duration;
        return $wm;
    }
}
