<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

class ServiceRegisterOpts extends AbstractModel
{
    public bool $ReplaceExistingChecks;
    public string $Token;

    public function __construct(
        bool $ReplaceExistingChecks = false,
        string $Token = '',
    ) {
        $this->ReplaceExistingChecks = $ReplaceExistingChecks;
        $this->Token = $Token;
}

    public function isReplaceExistingChecks(): bool
    {
        return $this->ReplaceExistingChecks;
    }

    public function setReplaceExistingChecks(bool $replaceExistingChecks): self
    {
        $this->ReplaceExistingChecks = $replaceExistingChecks;
        return $this;
    }

    public function getToken(): string
    {
        return $this->Token;
    }

    public function setToken(string $Token): ServiceRegisterOpts
    {
        $this->Token = $Token;
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
        $out->ReplaceExistingChecks = $this->ReplaceExistingChecks;
        $out->Token = $this->Token;
        return $out;
    }
}
