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
use DCarbone\PHPConsulAPI\MetaContainer;

class ACLOIDCAuthURLParams extends AbstractModel
{
    use MetaContainer;

    public string $AuthMethod;
    public string $RedirectURI;
    public string $ClientNonce;

    public function __construct(
        string $AuthMethod = '',
        string $RedirectURI = '',
        string $ClientNonce = '',
        null|\stdClass $Meta = null
    ) {
        $this->AuthMethod = $AuthMethod;
        $this->RedirectURI = $RedirectURI;
        $this->ClientNonce = $ClientNonce;
        $this->Meta = $Meta;
}

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
        $out->AuthMethod = $this->AuthMethod;
        $out->RedirectURI = $this->RedirectURI;
        $out->ClientNonce = $this->ClientNonce;
        if (null !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        return $out;
    }
}
