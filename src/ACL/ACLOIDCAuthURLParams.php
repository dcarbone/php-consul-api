<?php

declare(strict_types=1);

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * ACLOIDCAuthURLParams
 */
class ACLOIDCAuthURLParams extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_META => Transcoding::MAP_FIELD + [Transcoding::FIELD_OMITEMPTY => true],
    ];

    private const FIELD_META = 'Meta';

    /** @var string */
    public string $AuthMethod = '';
    /** @var string */
    public string $RedirectURI = '';
    /** @var string */
    public string $ClientNonce = '';
    /** @var \DCarbone\PHPConsulAPI\FakeMap|null */
    public ?FakeMap $Meta = null;

    /**
     * @return string
     */
    public function getAuthMethod(): string
    {
        return $this->AuthMethod;
    }

    /**
     * @param string $AuthMethod
     * @return \DCarbone\PHPConsulAPI\ACL\ACLOIDCAuthURLParams
     */
    public function setAuthMethod(string $AuthMethod): self
    {
        $this->AuthMethod = $AuthMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectURI(): string
    {
        return $this->RedirectURI;
    }

    /**
     * @param string $RedirectURI
     * @return \DCarbone\PHPConsulAPI\ACL\ACLOIDCAuthURLParams
     */
    public function setRedirectURI(string $RedirectURI): self
    {
        $this->RedirectURI = $RedirectURI;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientNonce(): string
    {
        return $this->ClientNonce;
    }

    /**
     * @param string $ClientNonce
     * @return \DCarbone\PHPConsulAPI\ACL\ACLOIDCAuthURLParams
     */
    public function setClientNonce(string $ClientNonce): self
    {
        $this->ClientNonce = $ClientNonce;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\FakeMap|null
     */
    public function getMeta(): ?FakeMap
    {
        return $this->Meta;
    }

    /**
     * @param mixed $Meta
     * @return \DCarbone\PHPConsulAPI\ACL\ACLOIDCAuthURLParams
     */
    public function setMeta($Meta): self
    {
        $this->Meta = FakeMap::parse($Meta);
        return $this;
    }
}
