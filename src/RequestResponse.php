<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

final class RequestResponse
{
    public RequestMeta $RequestMeta;
    public Time\Duration $Duration;
    public null|ResponseInterface $Response;
    public null|Error $Err;

    public function __construct(RequestMeta $meta, null|string|int|float|\DateInterval|Time\Duration $dur, null|ResponseInterface $resp, null|Error $err)
    {
        $this->RequestMeta = $meta;
        $this->Duration    = Time::Duration($dur);
        $this->Response    = $resp;
        $this->Err         = $err;
    }

    public function getRequestMeta(): RequestMeta
    {
        return $this->RequestMeta;
    }

    public function getDuration(): Time\Duration
    {
        return $this->Duration;
    }

    public function getResponse(): null|ResponseInterface
    {
        return $this->Response;
    }

    public function getErr(): null|Error
    {
        return $this->Err;
    }

    public function buildQueryMeta(): QueryMeta
    {
        // set some always-defined values
        $qm = new QueryMeta(
            RequestUrl: (string)$this->RequestMeta->uri,
            RequestTime: $this->Duration,
        );

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
            $qm->CacheAge = Time::Duration((int)$h * Time::Second);
        }

        return $qm;
    }

    public function buildWriteMeta(): WriteMeta
    {
        return new WriteMeta($this->Duration);
    }
}
