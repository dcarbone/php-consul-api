<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

use function DCarbone\PHPConsulAPI\PHPLib\parse_time;

class LeafCert extends AbstractType
{
    public string $SerialNumber;
    public string $CertPEM;
    public string $PrivateKeyPEM;
    public string $Service;
    public string $ServiceURI;
    public null|Time\Time $ValidAfter;
    public null|Time\Time $ValidBefore;
    public int $CreateIndex;
    public int $ModifyIndex;

    public function __construct(
        string $SerialNumber = '',
        string $CertPEM = '',
        string $PrivateKeyPEM = '',
        string $Service = '',
        string $ServiceURI = '',
        null|Time\Time $ValidAfter = null,
        null|Time\Time $ValidBefore = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        $this->SerialNumber = $SerialNumber;
        $this->CertPEM = $CertPEM;
        $this->PrivateKeyPEM = $PrivateKeyPEM;
        $this->Service = $Service;
        $this->ServiceURI = $ServiceURI;
        $this->ValidAfter = $ValidAfter;
        $this->ValidBefore = $ValidBefore;
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
    }

    public function getSerialNumber(): string
    {
        return $this->SerialNumber;
    }

    public function setSerialNumber(string $SerialNumber): self
    {
        $this->SerialNumber = $SerialNumber;
        return $this;
    }

    public function getCertPEM(): string
    {
        return $this->CertPEM;
    }

    public function setCertPEM(string $CertPEM): self
    {
        $this->CertPEM = $CertPEM;
        return $this;
    }

    public function getPrivateKeyPEM(): string
    {
        return $this->PrivateKeyPEM;
    }

    public function setPrivateKeyPEM(string $PrivateKeyPEM): self
    {
        $this->PrivateKeyPEM = $PrivateKeyPEM;
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

    public function getServiceURI(): string
    {
        return $this->ServiceURI;
    }

    public function setServiceURI(string $ServiceURI): self
    {
        $this->ServiceURI = $ServiceURI;
        return $this;
    }

    public function getValidAfter(): null|Time\Time
    {
        return $this->ValidAfter;
    }

    public function setValidAfter(null|Time\Time $ValidAfter): self
    {
        $this->ValidAfter = $ValidAfter;
        return $this;
    }

    public function getValidBefore(): null|Time\Time
    {
        return $this->ValidBefore;
    }

    public function setValidBefore(null|Time\Time $ValidBefore): self
    {
        $this->ValidBefore = $ValidBefore;
        return $this;
    }

    public function getCreateIndex(): int
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int $CreateIndex): self
    {
        $this->CreateIndex = $CreateIndex;
        return $this;
    }

    public function getModifyIndex(): int
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int $ModifyIndex): self
    {
        $this->ModifyIndex = $ModifyIndex;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('ValidAfter' === $k || 'ValidBefore' === $k) {
                $n->{$k} = null === $v ? null : parse_time($v);
                continue;
            }
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->SerialNumber = $this->SerialNumber;
        if ('' !== $this->CertPEM) {
            $out->CertPEM = $this->CertPEM;
        }
        if ('' !== $this->PrivateKeyPEM) {
            $out->PrivateKeyPEM = $this->PrivateKeyPEM;
        }
        $out->Service = $this->Service;
        $out->ServiceURI = $this->ServiceURI;
        if (null !== $this->ValidAfter && !$this->ValidAfter->isZero()) {
            $out->ValidAfter = $this->ValidAfter->format(DATE_RFC3339);
        }
        if (null !== $this->ValidBefore && !$this->ValidBefore->isZero()) {
            $out->ValidBefore = $this->ValidBefore->format(DATE_RFC3339);
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
