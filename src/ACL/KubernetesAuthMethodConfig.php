<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

class KubernetesAuthMethodConfig extends AbstractModel
{
    public string $Host;
    public string $CACert;
    public string $ServiceAccountJWT;

    public function __construct(
        string $Host = '',
        string $CACert = '',
        string $ServiceAccountJWT = ''
    ) {
        $this->Host = $Host;
        $this->CACert = $CACert;
        $this->ServiceAccountJWT = $ServiceAccountJWT;
}

    public function getHost(): string
    {
        return $this->Host;
    }

    public function setHost(string $Host): self
    {
        $this->Host = $Host;
        return $this;
    }

    public function getCACert(): string
    {
        return $this->CACert;
    }

    public function setCACert(string $CACert): self
    {
        $this->CACert = $CACert;
        return $this;
    }

    public function getServiceAccountJWT(): string
    {
        return $this->ServiceAccountJWT;
    }

    public function setServiceAccountJWT(string $ServiceAccountJWT): self
    {
        $this->ServiceAccountJWT = $ServiceAccountJWT;
        return $this;
    }

    /**
     * RenderToConfig converts this into a map[string]interface{} suitable for use
     * in the ACLAuthMethod.Config field.
     *
     * @return array
     */
    public function RenderToConfig(): array
    {
        return [
            'Host' => $this->Host,
            'CACert' => $this->CACert,
            'ServiceAccountJWT' => $this->ServiceAccountJWT,
        ];
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ('' !== $this->Host) {
            $out->Host = $this->Host;
        }
        if ('' !== $this->CACert) {
            $out->CACert = $this->CACert;
        }
        if ('' !== $this->ServiceAccountJWT) {
            $out->ServiceAccountJWT = $this->ServiceAccountJWT;
        }
        return $out;
    }
}
