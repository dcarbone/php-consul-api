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

class IntentionJWTRequirement extends AbstractModel
{
    public string $Name;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTClaimVerification> */
    public array $VerifyClaims;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTClaimVerification> $VerifyClaims
     */
    public function __construct(string $Name = '', array $VerifyClaims = [])
    {
        $this->Name = $Name;
        $this->setVerifyClaims(...$VerifyClaims);
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTClaimVerification>
     */
    public function getVerifyClaims(): array
    {
        return $this->VerifyClaims;
    }

    public function setVerifyClaims(IntentionJWTClaimVerification ...$VerifyClaims): self
    {
        $this->VerifyClaims = $VerifyClaims;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('VerifyClaims' === $k || 'verify_claims' === $k) {
                $n->VerifyClaims = [];
                foreach ($v as $vv) {
                    $n->VerifyClaims[] = IntentionJWTClaimVerification::jsonUnserialize($vv);
                }
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
        if ('' !== $this->Name) {
            $out->Name = $this->Name;
        }
        if ([] !== $this->VerifyClaims) {
            $out->VerifyClaims = $this->VerifyClaims;
        }
        return $out;
    }
}
