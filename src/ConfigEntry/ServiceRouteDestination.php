<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

class ServiceRouteDestination extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SERVICE                  => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_SERVICE_SUBSET           => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAMESPACE                => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_PREFIX_REWRITE           => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_REQUEST_TIMEOUT          => Transcoding::DURATION_FIELD + [
            Transcoding::FIELD_UNMARSHAL_AS => Transcoding::STRING,
            Transcoding::FIELD_OMITEMPTY    => true,
        ],
        self::FIELD_NUM_RETRIES              => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_RETRY_ON_CONNECT_FAILURE => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
        self::FIELD_RETRY_ON_STATUS_CODES    => Transcoding::OMITEMPTY_INTEGER_ARRAY_FIELD,
    ];

    private const FIELD_SERVICE                  = 'Service';
    private const FIELD_SERVICE_SUBSET           = 'ServiceSubset';
    private const FIELD_NAMESPACE                = 'Namespace';
    private const FIELD_PREFIX_REWRITE           = 'PrefixRewrite';
    private const FIELD_REQUEST_TIMEOUT          = 'RequestTimeout';
    private const FIELD_NUM_RETRIES              = 'NumRetries';
    private const FIELD_RETRY_ON_CONNECT_FAILURE = 'RetryOnConnectFailure';
    private const FIELD_RETRY_ON_STATUS_CODES    = 'RetryOnStatusCodes';

    public string $Service = '';
    public string $ServiceSubset = '';
    public string $Namespace = '';
    public string $PrefixRewrite = '';
    public Time\Duration $RequestTimeout;
    public int $NumRetries = 0;
    public bool $RetryOnConnectFailure = false;
    public array $RetryOnStatusCodes = [];

    public function getService(): string
    {
        return $this->Service;
    }

    public function setService(string $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getServiceSubset(): string
    {
        return $this->ServiceSubset;
    }

    public function setServiceSubset(string $ServiceSubset): self
    {
        $this->ServiceSubset = $ServiceSubset;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function getPrefixRewrite(): string
    {
        return $this->PrefixRewrite;
    }

    public function setPrefixRewrite(string $PrefixRewrite): self
    {
        $this->PrefixRewrite = $PrefixRewrite;
        return $this;
    }

    public function getRequestTimeout(): Time\Duration
    {
        return $this->RequestTimeout;
    }

    public function setRequestTimeout(Time\Duration $RequestTimeout): self
    {
        $this->RequestTimeout = $RequestTimeout;
        return $this;
    }

    public function getNumRetries(): int
    {
        return $this->NumRetries;
    }

    public function setNumRetries(int $NumRetries): self
    {
        $this->NumRetries = $NumRetries;
        return $this;
    }

    public function isRetryOnConnectFailure(): bool
    {
        return $this->RetryOnConnectFailure;
    }

    public function setRetryOnConnectFailure(bool $RetryOnConnectFailure): self
    {
        $this->RetryOnConnectFailure = $RetryOnConnectFailure;
        return $this;
    }

    public function getRetryOnStatusCodes(): array
    {
        return $this->RetryOnStatusCodes;
    }

    public function setRetryOnStatusCodes(array $RetryOnStatusCodes): self
    {
        $this->RetryOnStatusCodes = $RetryOnStatusCodes;
        return $this;
    }
}
