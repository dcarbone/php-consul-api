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
use DCarbone\PHPConsulAPI\FakeMap;
use DCarbone\PHPConsulAPI\Transcoding;

class ACLOIDCAuthURLParams extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_META => Transcoding::MAP_FIELD + [Transcoding::FIELD_OMITEMPTY => true],
    ];

    private const FIELD_META = 'Meta';

    public string $AuthMethod = '';
    public string $RedirectURI = '';
    public string $ClientNonce = '';
    public ?FakeMap $Meta = null;

    public function getAuthMethod(): string
    {
        return $this->AuthMethod;
    }

    public function setAuthMethod(string $AuthMethod): self
    {
        $this->AuthMethod = $AuthMethod;
        return $this;
    }

    public function getRedirectURI(): string
    {
        return $this->RedirectURI;
    }

    public function setRedirectURI(string $RedirectURI): self
    {
        $this->RedirectURI = $RedirectURI;
        return $this;
    }

    public function getClientNonce(): string
    {
        return $this->ClientNonce;
    }

    public function setClientNonce(string $ClientNonce): self
    {
        $this->ClientNonce = $ClientNonce;
        return $this;
    }

    public function getMeta(): ?FakeMap
    {
        return $this->Meta;
    }

    public function setMeta(mixed $Meta): self
    {
        $this->Meta = FakeMap::parse($Meta);
        return $this;
    }
}
