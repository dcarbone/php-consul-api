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

/**
 * ACLOIDCCallbackParams
 */
class ACLOIDCCallbackParams extends AbstractModel
{
    /** @var string */
    public string $AuthMethod = '';
    /** @var string */
    public string $State = '';
    /** @var string */
    public string $Code = '';
    /** @var string */
    public string $ClientNonce = '';

    /**
     * @return string
     */
    public function getAuthMethod(): string
    {
        return $this->AuthMethod;
    }

    /**
     * @param string $AuthMethod
     * @return \DCarbone\PHPConsulAPI\ACL\ACLOIDCCallbackParams
     */
    public function setAuthMethod(string $AuthMethod): self
    {
        $this->AuthMethod = $AuthMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->State;
    }

    /**
     * @param string $State
     * @return \DCarbone\PHPConsulAPI\ACL\ACLOIDCCallbackParams
     */
    public function setState(string $State): self
    {
        $this->State = $State;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->Code;
    }

    /**
     * @param string $Code
     * @return \DCarbone\PHPConsulAPI\ACL\ACLOIDCCallbackParams
     */
    public function setCode(string $Code): self
    {
        $this->Code = $Code;
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
     * @return \DCarbone\PHPConsulAPI\ACL\ACLOIDCCallbackParams
     */
    public function setClientNonce(string $ClientNonce): self
    {
        $this->ClientNonce = $ClientNonce;
        return $this;
    }
}
