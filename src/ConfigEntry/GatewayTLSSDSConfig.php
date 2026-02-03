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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class GatewayTLSSDSConfig extends AbstractType
{
    public string $ClusterName;
    public string $CertResource;

    public function __construct(
        string $ClusterName = '',
        string $CertResource = ''
    ) {
        $this->ClusterName = $ClusterName;
        $this->CertResource = $CertResource;
    }

    public function getClusterName(): string
    {
        return $this->ClusterName;
    }

    public function setClusterName(string $ClusterName): self
    {
        $this->ClusterName = $ClusterName;
        return $this;
    }

    public function getCertResource(): string
    {
        return $this->CertResource;
    }

    public function setCertResource(string $CertResource): self
    {
        $this->CertResource = $CertResource;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('cluster_name' === $k) {
                $n->ClusterName = (string)$v;
            } elseif ('cert_resource' === $k) {
                $n->CertResource = (string)$v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ('' !== $this->ClusterName) {
            $out->ClusterName = $this->ClusterName;
        }
        if ('' !== $this->CertResource) {
            $out->CertResource = $this->CertResource;
        }
        return $out;
    }
}
