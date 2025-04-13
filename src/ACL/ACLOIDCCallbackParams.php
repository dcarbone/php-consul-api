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

class ACLOIDCCallbackParams extends AbstractModel
{
    public string $AuthMethod;
    public string $State;
    public string $Code;
    public string $ClientNonce;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $AuthMethod = '',
        string $State = '',
        string $Code = '',
        string $ClientNonce = '',
    ) {
        $this->AuthMethod = $AuthMethod;
        $this->State = $State;
        $this->Code = $Code;
        $this->ClientNonce = $ClientNonce;
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
        }
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

    public function getState(): string
    {
        return $this->State;
    }

    public function setState(string $State): self
    {
        $this->State = $State;
        return $this;
    }

    public function getCode(): string
    {
        return $this->Code;
    }

    public function setCode(string $Code): self
    {
        $this->Code = $Code;
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new static();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
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
        $out->State = $this->State;
        $out->Code = $this->Code;
        $out->ClientNonce = $this->ClientNonce;
        return $out;
    }
}
