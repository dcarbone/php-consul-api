<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class AgentAuthorizeParams extends AbstractType
{
    public string $Target;
    public string $ClientCertURI;
    public string $ClientCertSerial;

    public function __construct(
        string $Target = '',
        string $ClientCertURI = '',
        string $ClientCertSerial = '',
    ) {
        $this->Target = $Target;
        $this->ClientCertURI = $ClientCertURI;
        $this->ClientCertSerial = $ClientCertSerial;
    }

    public function getTarget(): string
    {
        return $this->Target;
    }

    public function setTarget(string $Target): self
    {
        $this->Target = $Target;
        return $this;
    }

    public function getClientCertURI(): string
    {
        return $this->ClientCertURI;
    }

    public function setClientCertURI(string $ClientCertURI): self
    {
        $this->ClientCertURI = $ClientCertURI;
        return $this;
    }

    public function getClientCertSerial(): string
    {
        return $this->ClientCertSerial;
    }

    public function setClientCertSerial(string $ClientCertSerial): self
    {
        $this->ClientCertSerial = $ClientCertSerial;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Target = $this->Target;
        $out->ClientCertURI = $this->ClientCertURI;
        $out->ClientCertSerial = $this->ClientCertSerial;
        return $out;
    }
}

