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

use DCarbone\PHPConsulAPI\AbstractModel;

class ServiceSplit extends AbstractModel
{
    public float $Weight;
    public string $Service;
    public string $ServiceSubset;
    public string $Namespace;
    public string $Partition;
    public null|HTTPHeaderModifiers $RequestHeaders;
    public null|HTTPHeaderModifiers $ResponseHeaders;

    public function __construct(
        float $Weight = 0.0,
        string $Service = '',
        string $ServiceSubset = '',
        string $Namespace = '',
        string $Partition = '',
        null|HTTPHeaderModifiers $RequestHeaders = null,
        null|HTTPHeaderModifiers $ResponseHeaders = null
    ) {
        $this->Weight = $Weight;
        $this->Service = $Service;
        $this->ServiceSubset = $ServiceSubset;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
        $this->RequestHeaders = $RequestHeaders;
        $this->ResponseHeaders = $ResponseHeaders;
    }

    public function getWeight(): float
    {
        return $this->Weight;
    }

    public function setWeight(float $Weight): self
    {
        $this->Weight = $Weight;
        return $this;
    }

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

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public function getRequestHeaders(): ?HTTPHeaderModifiers
    {
        return $this->RequestHeaders;
    }

    public function setRequestHeaders(null|HTTPHeaderModifiers $RequestHeaders): self
    {
        $this->RequestHeaders = $RequestHeaders;
        return $this;
    }

    public function getResponseHeaders(): null|HTTPHeaderModifiers
    {
        return $this->ResponseHeaders;
    }

    public function setResponseHeaders(null|HTTPHeaderModifiers $ResponseHeaders): self
    {
        $this->ResponseHeaders = $ResponseHeaders;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('service_subset' === $k) {
                $n->ServiceSubset = $v;
            } elseif ('RequestHeaders' === $k || 'request_headers' === $k) {
                $n->RequestHeaders = HTTPHeaderModifiers::jsonUnserialize($v);
            } elseif ('ResponseHeaders' === $k || 'response_headers' === $k) {
                $n->ResponseHeaders = HTTPHeaderModifiers::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Weight = $this->Weight;
        if ('' !== $this->Service) {
            $out->Service = $this->Service;
        }
        if ('' !== $this->ServiceSubset) {
            $out->ServiceSubset = $this->ServiceSubset;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if (null !== $this->RequestHeaders) {
            $out->RequestHeaders = $this->RequestHeaders;
        }
        if (null !== $this->ResponseHeaders) {
            $out->ResponseHeaders = $this->ResponseHeaders;
        }
        return $out;
    }
}
