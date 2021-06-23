<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class KubernetesAuthMethodConfig
 */
class KubernetesAuthMethodConfig extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_HOST                => Hydration::OMITEMPTY_STRING_FIELD,
        self::FIELD_CA_CERT             => Hydration::OMITEMPTY_STRING_FIELD,
        self::FIELD_SERVICE_ACCOUNT_JWT => Hydration::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_HOST                = 'Host';
    private const FIELD_CA_CERT             = 'CACert';
    private const FIELD_SERVICE_ACCOUNT_JWT = 'ServiceAccountJWT';

    /** @var string */
    public string $Host = '';
    /** @var string */
    public string $CACert = '';
    /** @var string */
    public string $ServiceAccountJWT = '';

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->Host;
    }

    /**
     * @param string $Host
     * @return \DCarbone\PHPConsulAPI\ACL\KubernetesAuthMethodConfig
     */
    public function setHost(string $Host): self
    {
        $this->Host = $Host;
        return $this;
    }

    /**
     * @return string
     */
    public function getCACert(): string
    {
        return $this->CACert;
    }

    /**
     * @param string $CACert
     * @return \DCarbone\PHPConsulAPI\ACL\KubernetesAuthMethodConfig
     */
    public function setCACert(string $CACert): self
    {
        $this->CACert = $CACert;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceAccountJWT(): string
    {
        return $this->ServiceAccountJWT;
    }

    /**
     * @param string $ServiceAccountJWT
     * @return \DCarbone\PHPConsulAPI\ACL\KubernetesAuthMethodConfig
     */
    public function setServiceAccountJWT(string $ServiceAccountJWT): self
    {
        $this->ServiceAccountJWT = $ServiceAccountJWT;
        return $this;
    }

    /**
     * RenderToConfig converts this into a map[string]interface{} suitable for use
     * in the ACLAuthMethod.Config field.
     *
     * @return \DCarbone\PHPConsulAPI\FakeMap
     */
    public function RenderToConfig(): FakeMap
    {
        return new FakeMap(
            [
                self::FIELD_HOST                => $this->Host,
                self::FIELD_CA_CERT             => $this->CACert,
                self::FIELD_SERVICE_ACCOUNT_JWT => $this->ServiceAccountJWT,
            ]
        );
    }
}
