<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;
use DCarbone\PHPConsulAPI\PHPLib\MetaField;

class ACLOIDCAuthURLParams extends AbstractType
{
    use MetaField;

    public string $AuthMethod;
    public string $RedirectURI;
    public string $ClientNonce;

    /**
     * @param array<string,string> $Meta
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $AuthMethod = '',
        string $RedirectURI = '',
        string $ClientNonce = '',
        array $Meta = [],
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->AuthMethod = $AuthMethod;
        $this->RedirectURI = $RedirectURI;
        $this->ClientNonce = $ClientNonce;
        $this->setMeta($Meta);
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
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('Meta' === $k) {
                $n->setMeta($v);
            } else {
                $n->{$k} = $v;
            }
        }
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
