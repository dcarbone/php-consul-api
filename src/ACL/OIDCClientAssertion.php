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

class OIDCClientAssertion extends AbstractType
{
    /** @var array<string> */
    public array $Audience;
    public null|OIDCClientAssertionKey $PrivateKey;
    public string $KeyAlgorithm;

    /**
     * @param array<string> $Audience
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        array $Audience = [],
        null|OIDCClientAssertionKey $PrivateKey = null,
        string $KeyAlgorithm = '',
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->setAudience(...$Audience);
        $this->PrivateKey = $PrivateKey;
        $this->KeyAlgorithm = $KeyAlgorithm;
    }

    /**
     * @return array<string>
     */
    public function getAudience(): array
    {
        return $this->Audience;
    }

    public function setAudience(string ...$Audience): self
    {
        $this->Audience = $Audience;
        return $this;
    }

    public function getPrivateKey(): null|OIDCClientAssertionKey
    {
        return $this->PrivateKey;
    }

    public function setPrivateKey(null|OIDCClientAssertionKey $PrivateKey): self
    {
        $this->PrivateKey = $PrivateKey;
        return $this;
    }

    public function getKeyAlgorithm(): string
    {
        return $this->KeyAlgorithm;
    }

    public function setKeyAlgorithm(string $KeyAlgorithm): self
    {
        $this->KeyAlgorithm = $KeyAlgorithm;
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
            if ('PrivateKey' === $k) {
                $n->PrivateKey = null !== $v ? OIDCClientAssertionKey::jsonUnserialize($v) : null;
            } elseif ('Audience' === $k) {
                $n->setAudience(...($v ?? []));
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Audience = $this->Audience;
        if (null !== $this->PrivateKey) {
            $out->PrivateKey = $this->PrivateKey->jsonSerialize();
        }
        if ('' !== $this->KeyAlgorithm) {
            $out->KeyAlgorithm = $this->KeyAlgorithm;
        }
        return $out;
    }
}
