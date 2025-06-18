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

class IngressListener extends AbstractModel
{
    public int $Port;
    public string $Protocol;

    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\IngressService> */
    public array $Services;
    public null|GatewayTLSConfig $TLS;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\IngressService> $Services
     */
    public function __construct(
        int $Port = 0,
        string $Protocol = '',
        array $Services = [],
        null|GatewayTLSConfig $TLS = null
    ) {
        $this->Port = $Port;
        $this->Protocol = $Protocol;
        $this->setServices(...$Services);
        $this->TLS = $TLS;
    }

    public function getPort(): int
    {
        return $this->Port;
    }

    public function setPort(int $Port): self
    {
        $this->Port = $Port;
        return $this;
    }

    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    public function setProtocol(string $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\IngressService>
     */
    public function getServices(): array
    {
        return $this->Services;
    }

    public function setServices(IngressService ...$Services): self
    {
        $this->Services = $Services;
        return $this;
    }

    public function getTLS(): null|GatewayTLSConfig
    {
        return $this->TLS;
    }

    public function setTLS(null|GatewayTLSConfig $TLS): self
    {
        $this->TLS = $TLS;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('Services' === $k) {
                $n->Services = [];
                foreach ($v as $vv) {
                    $n->Services[] = IngressService::jsonUnserialize($vv);
                }
            } elseif ('TLS' === $k) {
                $n->TLS = null === $v ? null : GatewayTLSConfig::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $into;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->Port = $this->Port;
        $out->Protocol = $this->Protocol;
        $out->Services = $this->Services;
        if (null !== $this->TLS) {
            $out->TLS = $this->TLS;
        }
        return $out;
    }
}
