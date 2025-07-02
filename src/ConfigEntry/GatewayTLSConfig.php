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

class GatewayTLSConfig extends AbstractModel
{
    public bool $Enabled;
    public null|GatewayTLSSDSConfig $SDS;
    public string $TLSMinVersion;
    public string $TLSMaxVersion;
    /** @var array<string> */
    public array $CipherSuites;

    /**
     * @param array<string> $CipherSuites
     */
    public function __construct(
        bool $Enabled = false,
        null|GatewayTLSSDSConfig $SDS = null,
        string $TLSMinVersion = '',
        string $TLSMaxVersion = '',
        array $CipherSuites = []
    ) {
        $this->Enabled = $Enabled;
        $this->SDS = $SDS;
        $this->TLSMinVersion = $TLSMinVersion;
        $this->TLSMaxVersion = $TLSMaxVersion;
        $this->setCipherSuites(...$CipherSuites);
    }

    public function isEnabled(): bool
    {
        return $this->Enabled;
    }

    public function setEnabled(bool $Enabled): self
    {
        $this->Enabled = $Enabled;
        return $this;
    }

    public function getSDS(): null|GatewayTLSSDSConfig
    {
        return $this->SDS;
    }

    public function setSDS(null|GatewayTLSSDSConfig $SDS): self
    {
        $this->SDS = $SDS;
        return $this;
    }

    public function getTLSMinVersion(): string
    {
        return $this->TLSMinVersion;
    }

    public function setTLSMinVersion(string $TLSMinVersion): self
    {
        $this->TLSMinVersion = $TLSMinVersion;
        return $this;
    }

    public function getTLSMaxVersion(): string
    {
        return $this->TLSMaxVersion;
    }

    public function setTLSMaxVersion(string $TLSMaxVersion): self
    {
        $this->TLSMaxVersion = $TLSMaxVersion;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getCipherSuites(): array
    {
        return $this->CipherSuites;
    }

    public function setCipherSuites(string ...$CipherSuites): self
    {
        $this->CipherSuites = $CipherSuites;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('tls_min_version' === $k) {
                $n->TLSMinVersion = (string)$v;
            } elseif ('tls_max_version' === $k) {
                $n->TLSMaxVersion = (string)$v;
            } elseif ('CipherSuites' === $k || 'cipher_suites' === $k) {
                $n->setcipherSuites(...$v);
            } elseif ('SDS' === $k) {
                $n->SDS = GatewayTLSSDSConfig::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $into;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Enabled = $this->Enabled;
        if (null !== $this->SDS) {
            $out->SDS = $this->SDS->jsonSerialize();
        }
        if ('' !== $this->TLSMinVersion) {
            $out->TLSMinVersion = $this->TLSMinVersion;
        }
        if ('' !== $this->TLSMaxVersion) {
            $out->TLSMaxVersion = $this->TLSMaxVersion;
        }
        if ([] !== $this->CipherSuites) {
            $out->CipherSuites = $this->CipherSuites;
        }
        return $out;
    }
}
