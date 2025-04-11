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

class ACLLoginParams extends AbstractModel
{
    public string $AuthMethod;
    public string $BearerToken;
    public null|array $Meta;

    public function __construct(string $AuthMethod = '', string $BearerToken = '', null|array|\stdClass $Meta = null)
    {
        $this->AuthMethod = $AuthMethod;
        $this->BearerToken = $BearerToken;
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

    public function getBearerToken(): string
    {
        return $this->BearerToken;
    }

    public function setBearerToken(string $BearerToken): self
    {
        $this->BearerToken = $BearerToken;
        return $this;
    }

    public function getMeta(): array
    {
        return $this->Meta;
    }

    public function setMeta(null|array|\stdClass $Meta): self
    {
        $this->Meta = match($Meta) {
            null => null,
            default => (array)$Meta,
        };
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new static();
        foreach ($decoded as $k => $v) {
            if ('Meta' === $k) {
                $n->setMeta($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->AuthMethod = $this->AuthMethod;
        $out->BearerToken = $this->BearerToken;
        if (null !== $this->Meta && [] !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        return $out;
    }
}
