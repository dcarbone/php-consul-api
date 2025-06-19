<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

class IntentionPermission extends AbstractModel
{
    public IntentionAction $Action;
    public null|IntentionHTTPPermission $HTTP;
    public null|IntentionJWTRequirement $JWT;

    public function __construct(
        string|IntentionAction $Action = IntentionAction::UNDEFINED,
        null|IntentionHTTPPermission $HTTP = null,
        null|IntentionJWTRequirement $JWT = null,
    ) {
        $this->Action = $Action instanceof IntentionAction ? $Action : IntentionAction::from($Action);
        $this->HTTP = $HTTP;
        $this->JWT = $JWT;
    }

    public function getAction(): IntentionAction
    {
        return $this->Action;
    }

    public function setAction(IntentionAction $Action): self
    {
        $this->Action = $Action;
        return $this;
    }

    public function getHTTP(): null|IntentionHTTPPermission
    {
        return $this->HTTP;
    }

    public function setHTTP(null|IntentionHTTPPermission $HTTP): self
    {
        $this->HTTP = $HTTP;
        return $this;
    }

    public function getJWT(): null|IntentionJWTRequirement
    {
        return $this->JWT;
    }

    public function setJWT(null|IntentionJWTRequirement $JWT): self
    {
        $this->JWT = $JWT;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ($k === 'Action') {
                $n->{$k} = IntentionAction::from($v);
            } elseif ($k === 'HTTP') {
                $n->{$k} = null === $v ? null : IntentionHTTPPermission::jsonUnserialize($v);
            } elseif ($k === 'JWT') {
                $n->{$k} = null === $v ? null : IntentionJWTRequirement::jsonUnserialize($v);
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
        $out->Action = $this->Action->value;
        if (null !== $this->HTTP) {
            $out->HTTP = $this->HTTP;
        }
        if (null !== $this->JWT) {
            $out->JWT = $this->JWT;
        }
        return $out;
    }
}
